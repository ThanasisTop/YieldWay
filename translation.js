document.addEventListener('DOMContentLoaded', () => {
    const languageSelect = document.getElementById('languageSelect');
    languageSelect.addEventListener('change', translatePage);
	
	if(!localStorage.getItem("language")){
		localStorage.setItem("language", "gr");
	}
	
	if(languageSelect.value!=localStorage.getItem("language") && localStorage.getItem("language")){
		 languageSelect.value=localStorage.getItem("language");
		 translatePage();
	}
		
    function translatePage() {
		localStorage.setItem("language", languageSelect.value);
        const language = languageSelect.value;
        const elementsToTranslate = document.querySelectorAll('[data-translate]');

        elementsToTranslate.forEach(element => {
            const key = element.getAttribute('data-translate');
            getTranslation(key, language).then(translation => {
                element.textContent = translation;
            });
        });
    }

    function getTranslation(key, language) {
        const translations = {
            en: {
				home: 'Home',
				aboutus:'About Us',
				services:'Services',
				language:'Language',
				contact:'Contact',
                welcome: 'Welcome to YieldWay!',
				more:'More',
				story:'Story',
				mission:'Mission',
				vision:'Vision',
				hours:'9.00 am - 9.00 pm',
				aboutus2:'We Help Our Clients To Grow Their Business'
            },
            gr: {
				home: 'Αρχική',
				aboutus:'Σχετικά με εμάς',
				services:'Υπηρεσίες',
				language:'Γλώσσα',
				contact:'Επικοινωνία',
                welcome: 'Καλώς ήλθατε στη YieldWay!',
				more:'Περισσότερα',
				story:'Ιστορία',
				mission:'Αποστολή',
				vision:'Όραμα',
				hours:'9.00 πμ - 9.00 μμ',
				aboutus2:'Βοηθάμε τους πελάτες μας να αναπτύξουν την επιχείρησή τους'
            },
            
            // Add more translations as needed
		};;

        return new Promise((resolve, reject) => {
            if (translations[language] && translations[language][key]) {
                resolve(translations[language][key]);
            } else {
                reject('Translation not found');
            }
        });
    }
});
