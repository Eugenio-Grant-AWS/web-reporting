<script type="text/javascript">
$('.btn-change-pw').click(function(event) {
    event.preventDefault();

    var $passwordField = $('#password');
    var $passwordConfirmationField = $('#password_confirmation');
    var $this = $(this);

    // Check if the button is in the "Cancel" state (i.e., icon is fa-times)
    if ($this.find('.fa').hasClass('fa-times')) {
        // Clear the password fields
        $passwordField.val('');
        $passwordConfirmationField.val('');

        // Reset the button text back to "Change Password"
        $this.find('.fa').removeClass('fa-times').addClass('fa-lock');
        $this.find('span').text('{!! trans("laravelusers::forms.change-pw") !!}');  // Reset to "Change Password"

        // Hide the password change container
        $('.pw-change-container').slideUp(100);
    } else {
        // Toggle visibility of the password change container
        $('.pw-change-container').slideToggle(100);

        // Change the button icon to "Cancel"
        $this.find('.fa').removeClass('fa-lock').addClass('fa-times');

        // Change the button text to "Cancel"
        $this.find('span').text('{!! trans("laravelusers::forms.cancel") !!}');  // Change to "Cancel"
    }
});

</script>
