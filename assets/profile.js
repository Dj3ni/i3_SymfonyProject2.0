import "./styles/profile.css";

const BTN = document.querySelectorAll(".onglets-btn")
console.log(BTN);
let contents = document.querySelectorAll(".tab-content");

hideBtnContent();

for (const btn of BTN) {
    // console.log("Hello btn");
    btn.addEventListener("click", function(){
        hideBtnContent()
        let btnIndex = btn.getAttribute("data-index")
        contents[btnIndex].style.display = "block";
        BTN[btnIndex].classList.add("active")
    })
}

function hideBtnContent(){
    for (let i = 0; i < contents.length; i++) {
        console.log(contents[i]);
        contents[i].style.display = "none";
        BTN[i].classList.remove("active")
    }
}
