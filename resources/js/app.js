import './bootstrap';

// Expose axios and Echo globally for Blade components
if (typeof window.axios !== 'undefined') {
    console.log('Axios is available globally');
}

if (typeof window.Echo !== 'undefined') {
    console.log('Laravel Echo is available globally');
} else {
    console.warn('Laravel Echo was not initialized - check bootstrap.js');
}