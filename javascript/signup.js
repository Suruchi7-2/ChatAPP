const form = document.querySelector(".signup form"), //selecting form element
    continueBtn = form.querySelector(".button input"), //selecting button chat
    errorText = form.querySelector(".error-text"); //selecting errro dsiplaying wlement

form.onsubmit = (e) => {
        e.preventDefault();
        // preventing form from submitting
    }
    //basically ajax library as interface to pass request method to server/api end point.
continueBtn.onclick = () => { //onclick of button chat
    // start ajax
    let xhr = new XMLHttpRequest(); //create xml object Use XMLHttpRequest (XHR) objects to interact with servers. You can retrieve data from a URL without having to do a full page refresh. This enables a Web page to update just part of a page without disrupting what the user is doing.
    xhr.open("POST", "php/signup.php", true); //xhr.open sets request method,url and takes manyarameter,but we pass method post,url means o request of post method it goes to server file signup.php and async
    //onload property of xml
    xhr.onload = () => {
        //XMLHttpRequest.readyState: number ,Returns client's state.if it matches with xmlhttprequest key value done then.
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                let data = xhr.response; //gives response of passed url.
                if (data === "success") {
                    location.href = "users.php";
                } else {
                    errorText.style.display = "block";
                    errorText.textContent = data;
                }
            }
        }
    }
    let formData = new FormData(form);
    xhr.send(formData);
}