$(document).ready(function() {
  const OfficeInfos = [
    { key: "telephone1", value: "+30 6979745936" },
    { key: "telephone2", value: "+30 6983255149" },
    { key: "email", value: "info@yieldway.gr" },
    { key: "availableHours", value: "9:00 πμ - 9:00 μμ" }
  ];
  
  const SocialLinks = [
    { key: "facebook", value: "https://www.facebook.com/profile.php?id=61567904312909" },
    { key: "instagram", value: "https://www.instagram.com/yieldway.gr/" },
    { key: "linkedIn", value: "https://www.linkedIn.com" }
  ];

  
  OfficeInfos.forEach((item) => {
	  document.getElementById(item.key).innerText = item.value
	  item.key!='availableHours'?document.getElementById(item.key+'Footer').innerText = item.value:document.getElementById(item.key).innerText = item.value
	  });
  
  SocialLinks.forEach((item) => {
	  document.getElementById(item.key).href = item.value
	  document.getElementById(item.key+'Footer').href = item.value;
	  });
  
  
  
});