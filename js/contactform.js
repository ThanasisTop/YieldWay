function submitForm(isFromContact) {
	
	let name;
	let mobile;
	let msg;
	
	let subject;
	let messagebody;
	var recaptchaResponse = grecaptcha.getResponse();
	
	if(isFromContact){
		name=document.getElementById('name').value;
		mobile=document.getElementById('mobile').value;
		msg=document.getElementById('message').value;
		
		let email=document.getElementById('mail').value;
		
		subject="Επικοινωνία";
		messagebody ="<b>Όνομα:</b>"+name+"<br>"+
					"<b>Email:</b>"+email+"<br>"+
					"<b>Τηλέφωνο:</b>"+mobile+"<br>"+
					"<b>Μήνυμα:</b>"+msg+"<br>";
	}
	else{
		let email=document.getElementById('newsLetterMail').value;
		
		if(!email) return alert('Παρακαλώ συμπληρώστε το mail σας!');
		
		subject="Newsletter";
		messagebody="<b>Email:</b>"+email+"<br>";
	}
		
		
	var mail={
				SecureToken : "",
				To : "info@yieldway.gr",
				From : "info@yieldway.gr",
				Subject : subject,
				Body : messagebody
			};	
				
				
	var section=(isFromContact ? "sendButton":"newsLetterSection");			
	document.getElementById(section).innerHTML ='<div class="spinner-border text-primary" role="status" style="width: 2rem; height: 2rem;"></div>';
	
	Email.send(mail).then(
			function(message){
				if(message=="OK"){
					document.getElementById(section).innerHTML ='<p><i class="fa fa-check" aria-hidden="true" style="color:green"></i> Η αποστολή ήταν επιτυχής!</p>';
				}
				if(message!="OK"){
					document.getElementById("sendButton").innerHTML ='<p><i class="fa fa-times" aria-hidden="true" style="color:red"></i> Η αποστολή απέτυχε...</p>';
					console.log(message)
				}
			}
	);
}