function Toast(icon,message) {
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });
    Toast.fire({
        icon: icon,
        title: message
    })
}

// Toast('success', 'Operation completed successfully!')