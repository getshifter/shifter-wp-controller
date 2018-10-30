// Handler
function call_shifter_operation(action) {
  jQuery.ajax({
    method: "POST",
    url: ajax_object.ajax_url,
    data: { "action": action }
  }).done((response) => {
    console.log(response);
    console.log(ajax_object.ajax_url);
  });
};

// Terminate App
function terminate_app() {

    swal({
      title: 'Are you sure?',
      text: "Confirm to power down your Shifter app.",
      padding: '3em',
      showCancelButton: true,
      confirmButtonColor: 'transparent',
      cancelButtonColor: '#333',
      confirmButtonText: 'Terminate',
    })
    .then((result) => {
      if (result.value) {
        call_shifter_operation("shifter_app_terminate");
        swal(
          'App Terminated',
          'Check Shifter Dashboard for status or to resetart.',
          'success'
        ).then(() => window.close());
      }
    });

};

// Generate Artifact
function generate_artifact() {

  swal({
    title: 'Generate Artifact?',
    text: "While generating an Artifact you will not be able to access your WordPress app.",
    showCancelButton: true,
    confirmButtonColor: '#bc4e9c',
    cancelButtonColor: '#333',
    confirmButtonText: 'Generate',
    padding: '3em'
  })
  .then((result) => {
    if (result.value) {
      call_shifter_operation("shifter_app_generate");
      swal(
        'Generating artifact!',
        'Please check the Shifter dashboard',
        'success'
      ).then(() => window.close());
    }
  });

};

// Generate Artifact App
jQuery(document).on("click", "#wp-admin-bar-shifter_support_generate", function() {
  generate_artifact();
});

// Terminate App
jQuery(document).on("click", "#wp-admin-bar-shifter_support_terminate", function() {
  terminate_app();
});