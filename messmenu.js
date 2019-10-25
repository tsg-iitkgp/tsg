var hallnames = ["Azad", "BC Roy", "BRH", "Gokhale", "HJB", "JCB", "Nehru", "LBS", "LLR", "MMM", "MS", "MT", "Nivedita", "Patel", "RK", "Rani Laxmibai", "RP", "SAM", "SN-IG"];
window.onload = fillHalls;

function fillHalls() {
    var sel = document.getElementById("halls");

    for (let i = 0; i < hallnames.length; i++){
        var opt = document.createElement("option");
        opt.appendChild(document.createTextNode(hallnames[i]));
        let val = i + 1;
        opt.value = val;
        sel.appendChild(opt);
    }
}