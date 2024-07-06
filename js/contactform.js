function submitForm() {
	
	let name=document.getElementById('name').value;
	let mail=document.getElementById('mail').value;
	let mobile=document.getElementById('mobile').value;
	let subject=document.getElementById('subject').value;
	let message=document.getElementById('message').value;
	
	console.log(name)
	console.log(mail)
	console.log(mobile)
	console.log(subject)
	console.log(message)
	
    alert('Email sent!');
	return true;
}