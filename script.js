let navbar = document.querySelector('.navbar');
let search = document.querySelector('.search-box');

document.querySelector('#search-icon').onclick = () => {
    search.classList.toggle('active');
    navbar.classList.remove('active');
}

document.querySelector('#menu-icon').onclick = () => {
    navbar.classList.toggle('active');
    search.classList.remove('active');
}

window.onscroll = () => {
    search.classList.remove('active');
    navbar.classList.remove('active');
}


// Wait for the DOM to fully load before running the script
document.addEventListener('DOMContentLoaded', function() {
    // Get the cart icon and popup elements
    var cartIcon = document.getElementById('cart-icon');
    var cartPopup = document.getElementById('cart-popup');
    var closeBtn = document.getElementById('close-cart');

    // Open cart popup when cart icon is clicked
    cartIcon.onclick = function() {
        cartPopup.style.display = 'block';
    }

    // Close cart popup when close button is clicked
    closeBtn.onclick = function() {
        cartPopup.style.display = 'none';
    }

    // Close cart popup if the user clicks outside the popup
    window.onclick = function(event) {
        if (event.target === cartPopup) {
            cartPopup.style.display = 'none';
        }
    }
});
