////////////index.php//////////////////////////
			function isValidName(){
				var name = document.forms["logIn"]["name"].value;
				if (name == "" || name == null){
					alert("Please enter your name to begin your shopping session.");
					return false;
				}
				else if(/(^[a-zA-Z\-]+$)|(^[a-zA-Z\-]+\ [a-zA-Z\-]+$)/.test(name) == false){
					alert("Please enter a vaild name to begin your shopping session.");
					return false;					
				}
				else{
					return true;
				}
			}
//////////////gameShop.php//////////////////////////
			function validatePrice(){
				var text = document.forms["search"]["cutOff"].value;
				if((/^[0-9]{1,2}\.[0-9][0-9]$/.test(text) === true) || text=="" || text==null){
					return true;
				}
				else{
					alert("INPUT ERROR:\nPlease either enter a valid price (In the format: 00.00) or nothing.");
					return false;
				}
			}
////////////////basket.php/////////////////////////
			function isValidEmail(){
				var email =  document.forms["checkOut"]["email"].value;
				if(/^[^@]+@[^@]+\.[^@]+/.test(email)===false){
					alert("Please enter a VALID email address.");
					return false;
				}
				return true;
			}
			
			function isValidCardNum(){
				var cardNum =  document.forms["checkOut"]["card"].value;
				if(/^[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{4}$/.test(cardNum)===false){
					alert("Please enter a VALID card number.\nIn the form: xxxx-xxxx-xxxx-xxxx");
					return false;
				}
				return true;
			}
			
			function isValidAddress(){
				var a1 =  document.forms["checkOut"]["address1"].value;
				var a2 =  document.forms["checkOut"]["address2"].value;
				if(/^[A-Za-z0-9\-\ ]+$/.test(a1)===false || /^[A-Za-z0-9\-\ ]+$/.test(a2)===false){
					alert("Please enter a VALID address. Only alphanumeric. ('-' also allowed)");
					return false;
				}
				return true;
			}

			function isValidTown(){
				var town =  document.forms["checkOut"]["town"].value;
				if(/^[A-Za-z\-\ ]+$/.test(town)===false){
					alert("Please enter a VALID town. Only alphabet characters. ('-' also allowed)");
					return false;
				}
				return true;
			}			
			
			function isValidPost(){
				var postCode =  document.forms["checkOut"]["code"].value;
				if(/^[A-Z]{2}[0-9]{2}\ [0-9][A-Z]{2}$/.test(postCode)===false){
					alert("Please enter a VALID post code.");
					return false;
				}
				return true;
			}
			
			function isValidForm(){
				if (isValidEmail() && isValidCardNum() && isValidPost() && isValidTown() && isValidAddress()){
					return true;
				}
				else{
					return false;
				}
			}