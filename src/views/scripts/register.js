document.getElementById('phone').addEventListener('input', function (e) {
    let value = e.target.value.replace(/\D/g, ''); // Remove all non-digit characters
    if (value.length > 11) value = value.slice(0, 11); // Limit to 11 digits
    
    // Format as (dd) d.dddd.dddd
    if (value.length > 10) {
        value = value.replace(/^(\d{2})(\d{1})(\d{4})(\d{4})$/, '($1) $2 $3 $4');
    } else if (value.length > 6) {
        value = value.replace(/^(\d{2})(\d{1})(\d{0,4})/, '($1) $2 $3');
    } else if (value.length > 2) {
        value = value.replace(/^(\d{2})(\d{0,4})/, '($1) $2');
    } else {
        value = value.replace(/^(\d*)/, '($1');
    }

    e.target.value = value;
});
