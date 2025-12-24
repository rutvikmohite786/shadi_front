/**
 * Matrimony App - Main JavaScript
 */

(function() {
    'use strict';

    // ==========================================================================
    // DOM Ready
    // ==========================================================================
    document.addEventListener('DOMContentLoaded', function() {
        initNavbar();
        initAlerts();
        initForms();
        initAnimations();
    });

    // ==========================================================================
    // Navbar Toggle (Mobile)
    // ==========================================================================
    function initNavbar() {
        const toggle = document.querySelector('.navbar-toggle');
        const nav = document.querySelector('.navbar-nav');

        if (toggle && nav) {
            toggle.addEventListener('click', function() {
                nav.classList.toggle('show');
                
                // Update aria-expanded
                const expanded = nav.classList.contains('show');
                toggle.setAttribute('aria-expanded', expanded);
            });

            // Close menu when clicking outside
            document.addEventListener('click', function(e) {
                if (!toggle.contains(e.target) && !nav.contains(e.target)) {
                    nav.classList.remove('show');
                    toggle.setAttribute('aria-expanded', 'false');
                }
            });

            // Close menu on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && nav.classList.contains('show')) {
                    nav.classList.remove('show');
                    toggle.setAttribute('aria-expanded', 'false');
                    toggle.focus();
                }
            });
        }
    }

    // ==========================================================================
    // Auto-dismiss Alerts
    // ==========================================================================
    function initAlerts() {
        const alerts = document.querySelectorAll('.alert[data-auto-dismiss]');
        
        alerts.forEach(function(alert) {
            const delay = parseInt(alert.dataset.autoDismiss) || 5000;
            
            setTimeout(function() {
                dismissAlert(alert);
            }, delay);
        });

        // Close buttons
        const closeButtons = document.querySelectorAll('.alert .close-btn');
        closeButtons.forEach(function(btn) {
            btn.addEventListener('click', function() {
                const alert = btn.closest('.alert');
                dismissAlert(alert);
            });
        });
    }

    function dismissAlert(alert) {
        alert.style.opacity = '0';
        alert.style.transform = 'translateY(-10px)';
        
        setTimeout(function() {
            alert.remove();
        }, 300);
    }

    // ==========================================================================
    // Form Enhancements
    // ==========================================================================
    function initForms() {
        // Password visibility toggle
        const passwordToggles = document.querySelectorAll('.password-toggle');
        
        passwordToggles.forEach(function(toggle) {
            toggle.addEventListener('click', function() {
                const input = document.getElementById(toggle.dataset.target);
                const icon = toggle.querySelector('i, svg');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    toggle.setAttribute('aria-label', 'Hide password');
                    if (icon) icon.classList.replace('fa-eye', 'fa-eye-slash');
                } else {
                    input.type = 'password';
                    toggle.setAttribute('aria-label', 'Show password');
                    if (icon) icon.classList.replace('fa-eye-slash', 'fa-eye');
                }
            });
        });

        // Form validation styling
        const forms = document.querySelectorAll('form[data-validate]');
        
        forms.forEach(function(form) {
            form.addEventListener('submit', function(e) {
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                form.classList.add('was-validated');
            });

            // Real-time validation
            const inputs = form.querySelectorAll('input, select, textarea');
            inputs.forEach(function(input) {
                input.addEventListener('blur', function() {
                    validateInput(input);
                });
                
                input.addEventListener('input', function() {
                    if (input.classList.contains('is-invalid')) {
                        validateInput(input);
                    }
                });
            });
        });

        // Character counter for textareas
        const textareas = document.querySelectorAll('textarea[data-maxlength]');
        
        textareas.forEach(function(textarea) {
            const maxLength = parseInt(textarea.dataset.maxlength);
            const counter = document.createElement('div');
            counter.className = 'char-counter text-muted';
            counter.style.cssText = 'font-size: 0.875rem; text-align: right; margin-top: 0.25rem;';
            
            updateCounter();
            textarea.parentNode.appendChild(counter);
            
            textarea.addEventListener('input', updateCounter);
            
            function updateCounter() {
                const remaining = maxLength - textarea.value.length;
                counter.textContent = remaining + ' characters remaining';
                counter.style.color = remaining < 20 ? 'var(--danger)' : 'var(--gray-500)';
            }
        });
    }

    function validateInput(input) {
        const isValid = input.checkValidity();
        
        if (isValid) {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
        } else {
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
        }
    }

    // ==========================================================================
    // Scroll Animations
    // ==========================================================================
    function initAnimations() {
        const animatedElements = document.querySelectorAll('[data-animate]');
        
        if (animatedElements.length === 0) return;
        
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    const animation = entry.target.dataset.animate;
                    const delay = entry.target.dataset.animateDelay || 0;
                    
                    setTimeout(function() {
                        entry.target.classList.add('animate-' + animation);
                    }, delay);
                    
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1
        });
        
        animatedElements.forEach(function(el) {
            el.style.opacity = '0';
            observer.observe(el);
        });
    }

    // ==========================================================================
    // Utility Functions
    // ==========================================================================
    
    // AJAX helper
    window.ajaxRequest = function(url, options) {
        options = options || {};
        
        const defaults = {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
            }
        };
        
        const config = Object.assign({}, defaults, options);
        
        if (config.data && config.method !== 'GET') {
            config.body = JSON.stringify(config.data);
        }
        
        return fetch(url, config)
            .then(function(response) {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            });
    };

    // Debounce function
    window.debounce = function(func, wait) {
        let timeout;
        return function executedFunction() {
            const context = this;
            const args = arguments;
            
            clearTimeout(timeout);
            timeout = setTimeout(function() {
                func.apply(context, args);
            }, wait);
        };
    };

    // Format date
    window.formatDate = function(date, format) {
        const d = new Date(date);
        const options = {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        return d.toLocaleDateString('en-IN', options);
    };

    // Calculate age from date
    window.calculateAge = function(birthDate) {
        const today = new Date();
        const birth = new Date(birthDate);
        let age = today.getFullYear() - birth.getFullYear();
        const monthDiff = today.getMonth() - birth.getMonth();
        
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
            age--;
        }
        
        return age;
    };

    // Toast notifications
    window.showToast = function(message, type) {
        type = type || 'info';
        
        const toast = document.createElement('div');
        toast.className = 'toast alert alert-' + type;
        toast.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px; animation: slideIn 0.3s ease;';
        toast.innerHTML = message;
        
        document.body.appendChild(toast);
        
        setTimeout(function() {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            setTimeout(function() {
                toast.remove();
            }, 300);
        }, 3000);
    };

    // Confirm dialog
    window.confirmAction = function(message, callback) {
        if (confirm(message)) {
            callback();
        }
    };

    // Image preview
    window.previewImage = function(input, previewId) {
        const preview = document.getElementById(previewId);
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            
            reader.readAsDataURL(input.files[0]);
        }
    };

    // Smooth scroll to element
    window.scrollToElement = function(selector, offset) {
        offset = offset || 0;
        const element = document.querySelector(selector);
        
        if (element) {
            const top = element.getBoundingClientRect().top + window.pageYOffset - offset;
            window.scrollTo({
                top: top,
                behavior: 'smooth'
            });
        }
    };

    // Format number with commas
    window.formatNumber = function(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    };

    // ==========================================================================
    // Interest/Shortlist Actions
    // ==========================================================================
    
    // Global handler for interest forms (AJAX)
    function initInterestForms() {
        document.querySelectorAll('.interest-form').forEach(function(form) {
            // Skip if already initialized
            if (form.dataset.ajaxInitialized === 'true') {
                return;
            }
            form.dataset.ajaxInitialized = 'true';
            
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formElement = this;
                const button = formElement.querySelector('button[type="submit"]');
                const userId = formElement.getAttribute('data-user-id') || formElement.action.match(/\/(\d+)$/)?.[1];
                const originalButtonText = button.innerHTML;
                const originalButtonDisabled = button.disabled;
                
                // Disable button and show loading
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
                
                // Get CSRF token
                const csrfToken = formElement.querySelector('[name="_token"]')?.value || 
                                 document.querySelector('meta[name="csrf-token"]')?.content;
                
                fetch(formElement.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({})
                })
                .then(function(response) {
                    return response.json().then(function(data) {
                        if (!response.ok) {
                            throw new Error(data.message || 'Failed to send interest');
                        }
                        return data;
                    });
                })
                .then(function(data) {
                    if (data.success) {
                        // Update button state
                        button.innerHTML = '<i class="fas fa-check"></i> Interest Sent';
                        button.disabled = true;
                        button.classList.remove('btn-primary');
                        button.classList.add('btn-secondary');
                        
                        showToast(data.message || 'Interest sent successfully!', 'success');
                        
                        // Remove the card if it's in a grid (dashboard behavior)
                        const card = formElement.closest('.card') || formElement.closest('.profile-card');
                        const grid = card ? card.closest('.grid') : null;
                        
                        if (card && grid) {
                            // Remove card with fade out animation
                            setTimeout(function() {
                                card.style.transition = 'opacity 0.3s ease';
                                card.style.opacity = '0';
                                setTimeout(function() {
                                    card.remove();
                                    
                                    // Check if grid is empty
                                    if (grid && grid.children.length === 0) {
                                        const gridParent = grid.parentElement;
                                        if (gridParent) {
                                            gridParent.innerHTML = '<div class="card p-6 text-center"><i class="fas fa-check-circle fa-3x text-success mb-3" style="display: block;"></i><p class="text-muted">All profiles processed!</p></div>';
                                        }
                                    }
                                }, 300);
                            }, 500);
                        } else if (card && card.dataset.removeOnInterest === 'true') {
                            // Remove card if explicitly marked
                            setTimeout(function() {
                                card.style.transition = 'opacity 0.3s ease';
                                card.style.opacity = '0';
                                setTimeout(function() {
                                    card.remove();
                                }, 300);
                            }, 1000);
                        }
                    } else {
                        throw new Error(data.message || 'Failed to send interest');
                    }
                })
                .catch(function(error) {
                    console.error('Error:', error);
                    showToast(error.message || 'Failed to send interest. Please try again.', 'danger');
                    
                    // Restore button state
                    button.disabled = originalButtonDisabled;
                    button.innerHTML = originalButtonText;
                });
            });
        });
    }
    
    // Initialize interest forms on DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        initInterestForms();
    });
    
    // Re-initialize after dynamic content loads
    window.initInterestForms = initInterestForms;
    
    // Global handler for shortlist forms (AJAX)
    function initShortlistForms() {
        document.querySelectorAll('.shortlist-form').forEach(function(form) {
            // Skip if already initialized
            if (form.dataset.ajaxInitialized === 'true') {
                return;
            }
            form.dataset.ajaxInitialized = 'true';
            
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formElement = this;
                const button = formElement.querySelector('button[type="submit"]');
                const userId = formElement.getAttribute('data-user-id') || formElement.action.match(/\/(\d+)$/)?.[1];
                const originalButtonText = button.innerHTML;
                const originalButtonDisabled = button.disabled;
                
                // Determine if this is add or remove based on button state
                // If button has btn-secondary class, it means it's currently shortlisted (remove action)
                // If button has btn-outline class, it means it's not shortlisted (add action)
                const isCurrentlyShortlisted = button.classList.contains('btn-secondary');
                const isRemove = isCurrentlyShortlisted;
                
                // Disable button and show loading
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                
                // Get CSRF token
                const csrfToken = formElement.querySelector('[name="_token"]')?.value || 
                                 document.querySelector('meta[name="csrf-token"]')?.content;
                
                // Determine method and URL
                // Routes: POST /matches/shortlist/{user} for add, DELETE /matches/shortlist/{user} for remove
                const method = isRemove ? 'DELETE' : 'POST';
                let url = formElement.action;
                // Ensure URL is correct - remove any /add or /remove suffix
                url = url.replace(/\/add$/, '').replace(/\/remove$/, '');
                
                fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({})
                })
                .then(function(response) {
                    return response.json().then(function(data) {
                        if (!response.ok) {
                            throw new Error(data.message || 'Failed to update shortlist');
                        }
                        return data;
                    });
                })
                .then(function(data) {
                    if (data.success) {
                        // Toggle button state
                        if (isRemove) {
                            // Was shortlisted, now removed - show outline star
                            button.innerHTML = '<i class="far fa-star"></i>';
                            button.classList.remove('btn-secondary');
                            button.classList.add('btn-outline');
                            button.setAttribute('title', 'Add to Shortlist');
                        } else {
                            // Was not shortlisted, now added - show filled star
                            button.innerHTML = '<i class="fas fa-star"></i>';
                            button.classList.remove('btn-outline');
                            button.classList.add('btn-secondary');
                            button.setAttribute('title', 'Remove from Shortlist');
                        }
                        
                        showToast(data.message || (isRemove ? 'Removed from shortlist' : 'Added to shortlist!'), 'success');
                        
                        // Remove the card if it's in a grid and we're removing (dashboard behavior)
                        const card = formElement.closest('.card') || formElement.closest('.profile-card');
                        const grid = card ? card.closest('.grid') : null;
                        
                        if (card && grid && isRemove) {
                            // Only remove card if we're removing from shortlist
                            setTimeout(function() {
                                card.style.transition = 'opacity 0.3s ease';
                                card.style.opacity = '0';
                                setTimeout(function() {
                                    card.remove();
                                    
                                    // Check if grid is empty
                                    if (grid && grid.children.length === 0) {
                                        const gridParent = grid.parentElement;
                                        if (gridParent) {
                                            gridParent.innerHTML = '<div class="card p-6 text-center"><i class="fas fa-check-circle fa-3x text-success mb-3" style="display: block;"></i><p class="text-muted">All profiles processed!</p></div>';
                                        }
                                    }
                                }, 300);
                            }, 500);
                        }
                        
                        button.disabled = false;
                    } else {
                        throw new Error(data.message || 'Failed to update shortlist');
                    }
                })
                .catch(function(error) {
                    console.error('Error:', error);
                    showToast(error.message || 'Failed to update shortlist. Please try again.', 'danger');
                    
                    // Restore button state
                    button.disabled = originalButtonDisabled;
                    button.innerHTML = originalButtonText;
                });
            });
        });
    }
    
    // Initialize shortlist forms on DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        initShortlistForms();
    });
    
    // Re-initialize after dynamic content loads
    window.initShortlistForms = initShortlistForms;
    
    window.sendInterest = function(userId) {
        ajaxRequest('/interests/send/' + userId, {
            method: 'POST'
        })
        .then(function(response) {
            showToast('Interest sent successfully!', 'success');
            // Update UI
            const btn = document.querySelector('[data-interest-btn="' + userId + '"]');
            if (btn) {
                btn.textContent = 'Interest Sent';
                btn.disabled = true;
                btn.classList.add('btn-secondary');
                btn.classList.remove('btn-primary');
            }
        })
        .catch(function(error) {
            showToast('Failed to send interest. Please try again.', 'danger');
        });
    };

    window.handleShortlistToggle = function(userId, buttonElement) {
        const isShortlisted = buttonElement.getAttribute('data-shortlisted') === '1';
        const url = '/matches/shortlist/' + userId;
        const method = isShortlisted ? 'DELETE' : 'POST';
        
        // Disable button during request
        buttonElement.disabled = true;
        const originalHTML = buttonElement.innerHTML;
        buttonElement.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        
        ajaxRequest(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || 
                               document.querySelector('input[name="_token"]')?.value
            }
        })
        .then(function(response) {
            // Update button state
            if (isShortlisted) {
                // Was shortlisted, now removed
                buttonElement.classList.remove('btn-secondary');
                buttonElement.classList.add('btn-outline');
                buttonElement.setAttribute('data-shortlisted', '0');
                buttonElement.setAttribute('title', 'Add to Shortlist');
                buttonElement.innerHTML = '<i class="far fa-star"></i>';
                showToast('Removed from shortlist', 'info');
            } else {
                // Was not shortlisted, now added
                buttonElement.classList.remove('btn-outline');
                buttonElement.classList.add('btn-secondary');
                buttonElement.setAttribute('data-shortlisted', '1');
                buttonElement.setAttribute('title', 'Remove from Shortlist');
                buttonElement.innerHTML = '<i class="fas fa-star"></i>';
                showToast('Added to shortlist!', 'success');
            }
            buttonElement.disabled = false;
        })
        .catch(function(error) {
            // Restore original state on error
            buttonElement.innerHTML = originalHTML;
            buttonElement.disabled = false;
            showToast(isShortlisted ? 'Failed to remove from shortlist.' : 'Failed to add to shortlist. Please try again.', 'danger');
        });
    };

    window.addToShortlist = function(userId) {
        const btn = document.querySelector('.shortlist-btn[data-user-id="' + userId + '"]');
        if (btn) {
            handleShortlistToggle(userId, btn);
        } else {
            ajaxRequest('/matches/shortlist/' + userId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || 
                                   document.querySelector('input[name="_token"]')?.value
                }
            })
            .then(function(response) {
                showToast('Added to shortlist!', 'success');
            })
            .catch(function(error) {
                showToast('Failed to add to shortlist. Please try again.', 'danger');
            });
        }
    };

    window.removeFromShortlist = function(userId) {
        const btn = document.querySelector('.shortlist-btn[data-user-id="' + userId + '"]');
        if (btn) {
            handleShortlistToggle(userId, btn);
        } else {
            ajaxRequest('/matches/shortlist/' + userId, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || 
                                   document.querySelector('input[name="_token"]')?.value
                }
            })
            .then(function(response) {
                showToast('Removed from shortlist', 'info');
            })
            .catch(function(error) {
                showToast('Failed to remove from shortlist.', 'danger');
            });
        }
    };

})();

// Add CSS for toast animation
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(100%);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    .toast {
        transition: all 0.3s ease;
    }
`;
document.head.appendChild(style);

