// function swalLoader(title = "Generating export...", text = "Please wait") {
//     swal({
//           title: title,
//           text: text,
//           allowOutsideClick: false,
//           showConfirmButton: false,
//         willOpen: () => {
//             Swal.showLoading()
//         },
//     });
// }

// function swalError() {
//     swal({
//       title: "Error!",
//       text: "Please report to administrator!",
//       icon: "error",
//     });
// }

// function swalSuccess(title = "Finished!") {
//     swal({
//       title: title,
//       icon: "success",
//       timer: 1000,
//       showConfirmButton: false,
//     });
// }

function swalError(text, title = null) {
    
    if (title == null) {
        title = "Error";
    }
    
    swal({
        title: title,
        text: text,
        icon: "error",
        timer: 4000,
        buttons: false,
    });
}


function swalWarning(text, title = null) {
    
    if (title == null) {
        title = "Warning";
    }
    
    swal({
        title: title,
        text: text,
        icon: "warning",
        timer: 4000,
        buttons: false,
    });
}