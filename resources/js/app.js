import './bootstrap'; 
import toastr from 'toastr'; 

// Konfigurasi opsi toastr
toastr.options = {
    closeButton: true,
    progressBar: true,
    positionClass: "toast-top-right",
    showDuration: 300,
    hideDuration: 2000,
    timeOut: 2000,
    extendedTimeOut: 2000,
    showEasing: "swing",
    hideEasing: "linear",
    showMethod: "fadeIn",
    hideMethod: "fadeOut",
};

window.toastr = toastr;
