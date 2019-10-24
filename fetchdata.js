window.onload = getJSON;
function setQuery() {      // to process the url to be sent to the server
    const site = "http://gymk-back.herokuapp.com" + window.location.pathname;   
    let temp = site.split('/');
    let final = "http://gymk-back.herokuapp.com/" + temp[temp.length - 1];
    return final;
}

async function getJSON() {
    let policy = {
        mode: 'no-cors'
    }
    fetch('http://gymk-back.herokuapp.com/events', policy).then(function (response) {   // url in place of link later
        console.log(response);

    }).then(function (data) {
        console.log(data);   // constructTable(data);
    }).catch(function (error) {
        console.log(error);
    });
}

function constructTable() {
            var data = JSON.stringify('json file here')

            /* sample format of JSON file [
                {
                    "Book ID": "1",
                    "Book Name": "Computer Architecture",
                    "Category": "Computers",
                    "Price": "125.60"
                },
                {
                    "Book ID": "2",
                    "Book Name": "Asp.Net 4 Blue Book",
                    "Category": "Programming",
                    "Price": "56.00"
                },
                {
                    "Book ID": "3",
                    "Book Name": "Popular Science",
                    "Category": "Science",
                    "Price": "210.40"
                }
            ] */ //JSON.stringify('json file here');

            var heads = []

            for(let i = 0; i < data.length; i++){  // get headings
                for(var key in data[i]){
                    if(heads.indexOf(key) === -1){
                        heads.push(key);
                    }
                }
            }
            var table = document.getElementById('event');   // change id accordingly

            var tr = table.insertRow(-1);

            for(let i = 0; i < heads.length; i++){
                var th = document.createElement('th');
                th.innerHTML = heads[i];
                tr.appendChild(th);
            }

            for(let i = 0; i < data.length; i++){
                tr = table.insertRow(-1);
                for(let j = 0; j < heads.length; j++){

                    var tabcell = tr.insertCell(-1);
                    tabcell.innerHTML = data[i][heads[j]];
                }
            }

        }


/*
function getJSON() {
let policy = {
    mode: 'no-cors'
}
const response = await fetch('http://gymk-back.herokuapp.com/events', policy);
const myJson = await response.json();

fetch('http://gymk-back.herokuapp.com/events', policy).then(function (response) {
    return response.json();
}).then(function (data) {
    console.log(data);   // constructTable(data);
}).catch(function (error) {
    console.log(error);
});
}

function getQuery(url) {
    const response = await fetch(url);
    const result = await response.json();
    // console.log(JSON.stringify(myJson));
    return result;
}

var getJSON = function(url, callback) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', url, true);
    xhr.responseType = 'json';
    xhr.onload = function() {
        var status = xhr.status;
        if (status === 200) {
            callback(null, xhr.response);
        } else {
            callback(status, xhr.response);
        }
    };
    xhr.send();
};

getJSON(setQuery(),
    function(err, data) {
        if (err !== null) {
            alert('Something went wrong: ' + err);
        } else {
            alert('Your query count: ' + data.query.count);
        }
}); */
