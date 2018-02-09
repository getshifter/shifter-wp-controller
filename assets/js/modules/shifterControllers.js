
const swal = require('sweetalert2');

export function terminate_app() {
  jQuery(document).on("click", "#wp-admin-bar-shifter_support_terminate", function() {
    swal({
      title: 'Are you sure?',
      text: "Confirm to power down your Shifter app.",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#bc4e9c',
      cancelButtonColor: '#333',
      confirmButtonText: 'Terminate'
    })
    .then((isOK) => {
      if (isOK) {
        call_shifter_operation("shifter_app_terminate");
        swal("Your Shifter app is terminated. Check your dashboard!", {icon: "success"})
        .then(() => window.close());
      }
    });
  });

};


export function generate_artifact() {
  jQuery(document).on("click", "#wp-admin-bar-shifter_support_generate", function() {
    swal({
      title: 'Generate Artifact?',
      text: "While generating an Artifact you will not be able to access your WordPress app.",
      type: 'info',
      showCancelButton: true,
      confirmButtonColor: '#bc4e9c',
      cancelButtonColor: '#333',
      confirmButtonText: 'Generate'
    })
    .then((isOK) => {
      if (isOK) {
        call_shifter_operation("shifter_app_generate");
        swal("Generating artifact is starting now. Check your dashboard!", {icon: "success"})
        .then(() => window.close());
      }
    });
  });
}


export function call_shifter_operation(action) {
  jQuery.ajax({
    method: "POST",
    url: ajax_object.ajax_url,
    data: { "action": action }
  }).done((response) => {
    console.log(response);
    console.log(ajax_object.ajax_url);
  });
};