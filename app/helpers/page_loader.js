function redirectToPage() {
    // Get the cad value from the hidden input field
    var cadValue = encodeURIComponent(document.querySelector('input[name="cad"]').value);
    
    // Construct the URL with the parameters
    var url = 'index.php?page=cad-lics-test&cad=' + cadValue;
    
    // Redirect to the new URL
    window.location.href = url;
}
