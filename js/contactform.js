function submitForm(isFromContact) {
	
	let name;
	let mobile;
	let message;
	
	let subject;
	let messagebody;
	
	if(isFromContact){
		name=document.getElementById('name').value;
		mobile=document.getElementById('mobile').value;
		message=document.getElementById('message').value;
		
		let email=document.getElementById('mail').value;
		
		subject="Επικοινωνία";
		messagebody ="<b>Όνομα:</b>"+name+"<br>"+
					"<b>Email:</b>"+email+"<br>"+
					"<b>Τηλέφωνο:</b>"+mobile+"<br>"+
					"<b>Μήνυμα:</b>"+message+"<br>";
	}
	else{
		let email=document.getElementById('newsLetterMail').value;
		
		if(!email) return alert('Παρακαλώ συμπληρώστε το mail σας!');
		
		subject="Newsletter";
		messagebody="<b>Email:</b>"+email+"<br>";
	}
		
		
	var mail={
				SecureToken : "d51e30f7-3acc-4314-8f9c-2e8d79110562",
				To : "info@yieldway.com",
				From : "info@yieldway.com",
				Subject : subject,
				Body : messagebody
			};	
				
				
	var section=(isFromContact ? "sendButton":"newsLetterSection");			
	document.getElementById(section).innerHTML ='<p>Αποστολή...</p>';
	
	//Sent e-mail
	const mailPromise = new Promise(function(myResolve, myReject){
		//setTimeout(function(){myReject(new Error('send mail failed'));}, "3000"); //mail failed
		setTimeout(function(){myResolve();}, "2000"); //mail success
	});
	
	mailPromise.then(
		function(){
			document.getElementById(section).innerHTML ='<p><i class="fa fa-check" aria-hidden="true" style="color:green"></i> Η αποστολή ήταν επιτυχής!</p>';
			},
		function(error){
			document.getElementById("sendButton").innerHTML ='<p><i class="fa fa-times" aria-hidden="true" style="color:red"></i> Η αποστολή απέτυχε...</p>';
		}
	);
}