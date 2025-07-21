document.addEventListener('alpine:init', () => {
    Alpine.directive('scroll-drag', (el) => {
        let isDown = false;
        let startX;
        let scrollLeft;

        el.addEventListener('mousedown', (e) => {
            isDown = true;
            el.classList.add('active-drag-cursor'); // Add class for grabbing cursor
            startX = e.pageX - el.offsetLeft;
            scrollLeft = el.scrollLeft;
        });

        // Use window listeners for mouseup/mouseleave to handle cases
        // where the mouse leaves the element while still dragging
        window.addEventListener('mouseup', () => {
            isDown = false;
            el.classList.remove('active-drag-cursor');
        });

        window.addEventListener('mouseleave', () => { // For mouse leaving browser window
            isDown = false;
            el.classList.remove('active-drag-cursor');
        });

        el.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault(); // Prevent text selection etc.
            const x = e.pageX - el.offsetLeft;
            const walk = (x - startX) * 1.5; // Adjust scroll speed (multiplier)
            el.scrollLeft = scrollLeft - walk;
        });

        // Add initial cursor style
        el.style.cursor = 'grab';
    });
});