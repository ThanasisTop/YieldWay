$(document).ready(function () {
            $('#contactForm').on('submit', function (e) {
                e.preventDefault(); // Prevent default form submission
                
                
				var isFromContact= true;
				var section=(isFromContact ? "sendButton":"newsLetterSection");	
				
				// Collect form data
                const formData = {
                    name: $('#name').val(),
                    email: $('#mail').val(),
                    message: $('#message').val(),
					subject: $('#subject').val(),
					isFromContact: isFromContact
                };
				console.log(formData)
				document.getElementById(section).innerHTML ='<div class="spinner-border text-primary" role="status" style="width: 2rem; height: 2rem;"></div>';
				
                // AJAX request
                $.ajax({
                    url: 'sendmail.php',
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function (response) {
						console.log(response)
                        if (response.success) {
                            document.getElementById(section).innerHTML ='<p><i class="fa fa-check" aria-hidden="true" style="color:green"></i> Η αποστολή ήταν επιτυχής!</p>';
                        } else {
							console.log(response)
                            document.getElementById("sendButton").innerHTML ='<p><i class="fa fa-times" aria-hidden="true" style="color:red"></i> Η αποστολή απέτυχε...</p>';
                        }
                    },
                    error: function (error) {
						console.log(error)
                        document.getElementById("sendButton").innerHTML ='<p><i class="fa fa-times" aria-hidden="true" style="color:red"></i> Η αποστολή απέτυχε...</p>';
                    }
                });
            });
			
			
			$('#newsletter').on('submit', function (e) {
                e.preventDefault(); // Prevent default form submission
                
				var isFromContact = false;
				const section="newsLetterSection";	
				
				// Collect form data
                const formData = {
                    email: $('#newsLetterMail').val(),
					subject: "Newsletter",
					isFromContact: isFromContact
                };
				console.log(formData)
				document.getElementById(section).innerHTML ='<div class="spinner-border text-primary" role="status" style="width: 2rem; height: 2rem;"></div>';
				
                // AJAX request
                $.ajax({
                    url: 'sendmail.php',
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function (response) {
						console.log(response)
                        if (response.success) {
                            document.getElementById(section).innerHTML ='<p><i class="fa fa-check" aria-hidden="true" style="color:green"></i> Η αποστολή ήταν επιτυχής!</p>';
                        } else {
							console.log(response)
                            document.getElementById(section).innerHTML ='<p><i class="fa fa-times" aria-hidden="true" style="color:red"></i> Η αποστολή απέτυχε...</p>';
                        }
                    },
                    error: function (error) {
						console.log(error)
                        document.getElementById(section).innerHTML ='<p><i class="fa fa-times" aria-hidden="true" style="color:red"></i> Η αποστολή απέτυχε...</p>';
                    }
                });
            });
});