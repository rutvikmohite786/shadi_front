<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;
use Exception;

class EncryptionService
{
    /**
     * Encrypt a message using AES-256-CBC
     * 
     * @param string $text Plain text message to encrypt
     * @return string Encrypted message in format: iv:encryptedData
     */
    public function encrypt(string $text): string
    {
        if (empty($text)) {
            return $text;
        }

        try {
            // Use Laravel's encryption but with custom format for compatibility with Node.js
            $key = $this->getEncryptionKey();
            $iv = random_bytes(16); // Initialization vector
            
            $encrypted = openssl_encrypt($text, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
            
            if ($encrypted === false) {
                throw new Exception('Encryption failed');
            }
            
            // Return IV and encrypted data separated by colon (hex encoded)
            return bin2hex($iv) . ':' . bin2hex($encrypted);
        } catch (Exception $e) {
            \Log::error('Encryption error: ' . $e->getMessage());
            throw new Exception('Failed to encrypt message');
        }
    }

    /**
     * Decrypt a message
     * 
     * @param string $encryptedText Encrypted message in format: iv:encryptedData
     * @return string Decrypted plain text message
     */
    public function decrypt(string $encryptedText): string
    {
        if (empty($encryptedText)) {
            return $encryptedText;
        }

        try {
            // Check if already decrypted (for backward compatibility)
            if (strpos($encryptedText, ':') === false) {
                return $encryptedText;
            }

            $key = $this->getEncryptionKey();
            $parts = explode(':', $encryptedText, 2);

            if (count($parts) !== 2) {
                // If format is wrong, return as is (might be old unencrypted data)
                return $encryptedText;
            }

            $iv = hex2bin($parts[0]);
            $encrypted = hex2bin($parts[1]);

            if ($iv === false || $encrypted === false) {
                throw new Exception('Invalid encrypted data format');
            }

            $decrypted = openssl_decrypt($encrypted, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

            if ($decrypted === false) {
                throw new Exception('Decryption failed');
            }

            return $decrypted;
        } catch (Exception $e) {
            \Log::error('Decryption error: ' . $e->getMessage());
            // Return original text if decryption fails (for backward compatibility)
            return $encryptedText;
        }
    }

    /**
     * Get encryption key (32 bytes for AES-256)
     * 
     * @return string Encryption key
     */
    private function getEncryptionKey(): string
    {
        $key = config('app.key');
        
        // Remove 'base64:' prefix if present
        if (strpos($key, 'base64:') === 0) {
            $key = base64_decode(substr($key, 7));
        }
        
        // Ensure key is exactly 32 bytes for AES-256
        return substr(hash('sha256', $key, true), 0, 32);
    }
}



