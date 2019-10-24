function setQuery() {      // to process the url to be sent to the server
    const site = "http://gymk-back.herokuapp.com" + window.location.pathname;   
    let temp = site.split('/');
    let final = "http://gymk-back.herokuapp.com/" + temp[temp.length - 1];
    return final;
}

/*function getQuery(url) {
    const response = await fetch(url);
    const result = await response.json();
    // console.log(JSON.stringify(myJson));
    return result;
}*/

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
});

function getHeadings(httpGet(site)){
    let headings = [] // for gc, open iit, events
    headings = ;[...Object.keys(result[0])] // headers in the table

    var table = document.getElementById('#event');
    table.innerHTML += "<tr>";
    for(var i = 0; i < headings.length; i++){
    table.innerHTML += '<th>' + headings[i] + '<th/>';
    }
    table.innerHTML += '<tr/>';
}

var txt = httpGet(site);
var obj = JSON.parse(txt);
function constructTable(tableid){
    var cols = Headers(obj, tableid);

    for(var i = 0; i < obj.length; i++){
        var row = $('<tr/>');
        for ( var colIndex = 0; colIndex < cols.length; colIndex++){
            var val = obj[i][cols[colIndex]];

            if (val == null) val = "";
            row.append($('<td/>').html(val));
        }
        $(tableid).append(row);
    }
}

function Headers(obj, tableid){
    var columns = [];
    var header = $('<tr/>');

    for(var i = 0;i < obj.length; i++){
        var row = obj[i];

        for(var k in row){
            if($.inArray(k, columns) == -1 ){
                columns.push(k);
                header.append($('<th/>').html(k));
            }
        }
        $(tableid).append(header);
        return columns;
    }

}
