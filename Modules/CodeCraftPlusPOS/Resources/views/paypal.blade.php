
<button type="button" id="donate-button" title="Donar a CodeCrafters" class="btn bg-yellow btn-flat m-6 btn-xs m-5 pull-right">
    <strong><i class="fab fa-paypal fa-lg"></i></strong>
</button>

<script src="https://www.paypalobjects.com/donate/sdk/donate-sdk.js" charset="UTF-8"></script>
<script>
    PayPal.Donation.Button({
        env: 'production',
        hosted_button_id: 'E7LUMXZTG7FE6',
        image: {
            src: 'https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif',
            alt: 'Donate with PayPal button',
            title: 'Donar a CodeCrafters'
        }
    }).render('#donate-button');

    
</script>


