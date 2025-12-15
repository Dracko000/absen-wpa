// Responsive JavaScript utilities

document.addEventListener('DOMContentLoaded', function() {
    // Handle responsive video sizing for QR scanner
    const video = document.getElementById('video');
    if (video) {
        // Adjust video size based on screen
        const resizeVideo = () => {
            const maxWidth = Math.min(400, window.innerWidth * 0.9); // Max 400px or 90% of screen width
            video.style.maxWidth = maxWidth + 'px';
        };
        
        resizeVideo();
        window.addEventListener('resize', resizeVideo);
    }

    // Handle responsive modal sizing
    const modals = document.querySelectorAll('[id*="Modal"]');
    modals.forEach(modal => {
        modal.addEventListener('show', function() {
            if (window.innerWidth < 768) {
                // On mobile, make modal full width
                const modalDialog = this.querySelector('.modal-dialog');
                if (modalDialog) {
                    modalDialog.classList.add('w-full', 'mx-2');
                }
            }
        });
    });

    // Responsive table handling - allow horizontal scroll on small screens
    const tables = document.querySelectorAll('.overflow-x-auto table');
    tables.forEach(table => {
        const container = document.createElement('div');
        container.className = 'overflow-x-auto';
        container.appendChild(table.cloneNode(true));
        table.parentNode.replaceChild(container, table);
    });

    // Adjust layout for smaller screens
    const adjustLayoutForMobile = () => {
        if (window.innerWidth < 768) {
            // Add mobile-specific classes
            document.body.classList.add('mobile-layout');
        } else {
            document.body.classList.remove('mobile-layout');
        }
    };

    adjustLayoutForMobile();
    window.addEventListener('resize', adjustLayoutForMobile);
    
    // Handle touch events for better mobile experience
    const buttons = document.querySelectorAll('button');
    buttons.forEach(button => {
        button.addEventListener('touchstart', function() {
            this.classList.add('active');
        });
        
        button.addEventListener('touchend', function() {
            this.classList.remove('active');
            // Small delay to ensure visual feedback
            setTimeout(() => {
                this.classList.remove('active');
            }, 150);
        });
    });
});