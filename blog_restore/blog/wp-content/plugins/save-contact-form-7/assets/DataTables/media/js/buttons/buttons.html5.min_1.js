(function (g) {
    "function" === typeof define && define.amd ? define(["jquery", "datatables.net", "datatables.net-buttons"], function (d) {
        return g(d, window, document)
    }) : "object" === typeof exports ? module.exports = function (d, f) {
        d || (d = window);
        if (!f || !f.fn.dataTable)
            f = require("datatables.net")(d, f).$;
        f.fn.dataTable.Buttons || require("datatables.net-buttons")(d, f);
        return g(f, d, d.document)
    } : g(jQuery, window, document)
})(function (g, d, f, k) {
    var l = g.fn.dataTable,
            j;
    if ("undefined" !== typeof navigator && /MSIE [1-9]\./.test(navigator.userAgent))
        j =
                void 0;
    else {
        var v = d.document,
                o = v.createElementNS("http://www.w3.org/1999/xhtml", "a"),
                D = "download" in o,
                p = d.webkitRequestFileSystem,
                w = d.requestFileSystem || p || d.mozRequestFileSystem,
                E = function (a) {
                    (d.setImmediate || d.setTimeout)(function () {
                        throw a;
                    }, 0)
                },
                q = 0,
                r = function (a) {
                    var b = function () {
                        "string" === typeof a ? (d.URL || d.webkitURL || d).revokeObjectURL(a) : a.remove()
                    };
                    d.chrome ? b() : setTimeout(b, 500)
                },
                s = function (a, b, e) {
                    for (var b = [].concat(b), c = b.length; c--; ) {
                        var d = a["on" + b[c]];
                        if ("function" === typeof d)
                            try {
                                d.call(a,
                                        e || a)
                            } catch (h) {
                                E(h)
                            }
                    }
                },
                y = function (a) {
                    return /^\s*(?:text\/\S*|application\/xml|\S*\/\S*\+xml)\s*;.*charset\s*=\s*utf-8/i.test(a.type) ? new Blob(["﻿", a], {
                        type: a.type
                    }) : a
                },
                A = function (a, b) {
                    var a = y(a),
                            e = this,
                            c = a.type,
                            x = !1,
                            h, g, z = function () {
                                s(e, ["writestart", "progress", "write", "writeend"])
                            },
                            f = function () {
                                if (x || !h)
                                    h = (d.URL || d.webkitURL || d).createObjectURL(a);
                                g ? g.location.href = h : d.open(h, "_blank") === k && "undefined" !== typeof safari && (d.location.href = h);
                                e.readyState = e.DONE;
                                z();
                                r(h)
                            },
                            n = function (a) {
                                return function () {
                                    if (e.readyState !==
                                            e.DONE)
                                        return a.apply(this, arguments)
                                }
                            },
                            i = {
                                create: !0,
                                exclusive: !1
                            },
                    j;
                    e.readyState = e.INIT;
                    b || (b = "download");
                    if (D)
                        h = (d.URL || d.webkitURL || d).createObjectURL(a), o.href = h, o.download = b, c = v.createEvent("MouseEvents"), c.initMouseEvent("click", !0, !1, d, 0, 0, 0, 0, 0, !1, !1, !1, !1, 0, null), o.dispatchEvent(c), e.readyState = e.DONE, z(), r(h);
                    else {
                        d.chrome && (c && "application/octet-stream" !== c) && (j = a.slice || a.webkitSlice, a = j.call(a, 0, a.size, "application/octet-stream"), x = !0);
                        p && "download" !== b && (b += ".download");
                        if ("application/octet-stream" ===
                                c || p)
                            g = d;
                        w ? (q += a.size, w(d.TEMPORARY, q, n(function (c) {
                            c.root.getDirectory("saved", i, n(function (c) {
                                var d = function () {
                                    c.getFile(b, i, n(function (b) {
                                        b.createWriter(n(function (c) {
                                            c.onwriteend = function (a) {
                                                g.location.href = b.toURL();
                                                e.readyState = e.DONE;
                                                s(e, "writeend", a);
                                                r(b)
                                            };
                                            c.onerror = function () {
                                                var a = c.error;
                                                a.code !== a.ABORT_ERR && f()
                                            };
                                            ["writestart", "progress", "write", "abort"].forEach(function (a) {
                                                c["on" + a] = e["on" + a]
                                            });
                                            c.write(a);
                                            e.abort = function () {
                                                c.abort();
                                                e.readyState = e.DONE
                                            };
                                            e.readyState = e.WRITING
                                        }), f)
                                    }),
                                            f)
                                };
                                c.getFile(b, {
                                    create: false
                                }, n(function (a) {
                                    a.remove();
                                    d()
                                }), n(function (a) {
                                    a.code === a.NOT_FOUND_ERR ? d() : f()
                                }))
                            }), f)
                        }), f)) : f()
                    }
                },
                i = A.prototype;
        "undefined" !== typeof navigator && navigator.msSaveOrOpenBlob ? j = function (a, b) {
            return navigator.msSaveOrOpenBlob(y(a), b)
        } : (i.abort = function () {
            this.readyState = this.DONE;
            s(this, "abort")
        }, i.readyState = i.INIT = 0, i.WRITING = 1, i.DONE = 2, i.error = i.onwritestart = i.onprogress = i.onwrite = i.onabort = i.onerror = i.onwriteend = null,
                j = function (a, b) {
                    return new A(a, b)
                })
    }
    var t = function (a,
            b) {
        var e = "*" === a.filename && "*" !== a.title && a.title !== k ? a.title : a.filename;
        -1 !== e.indexOf("*") && (e = e.replace("*", g("title").text()));
        e = e.replace(/[^a-zA-Z0-9_\u00A1-\uFFFF\.,\-_ !\(\)]/g, "");
        return b === k || !0 === b ? e + a.extension : e
    },
            F = function (a) {
                a = a.title;
                return -1 !== a.indexOf("*") ? a.replace("*", g("title").text()) : a
            },
            u = function (a) {
                return a.newline ? a.newline : navigator.userAgent.match(/Windows/) ? "\r\n" : "\n"
            },
            //changed

            B = function (a, bx, c) {
                
                var ax = new Array();
                var by=new Array();
                for (i = 0; i < a.length; i++)
                {
                    // for data or values
                    a[i]=a[i].toString().replace(/"/g,'');  //replace(/[\[\]']+/g,''
                    a[i]=a[i].toString().replace(/[\[\]']+/g,'');
                    ax.push(a[i] + "\n");
                }
                
                for (i = 0; i < bx.length; i++)
                {   
                    if(bx[i] != "" || bx[i] != null )
                    {
                    // for columns
                
                    bx[i]=bx[i].toString().replace(/"/g,'');  //replace(/[\[\]']+/g,''
                    bx[i]=bx[i].toString().replace(/[\[\]']+/g,'');
                    by.push(bx[i] + "\n");
                    }
                }
                
//                var cfx=new Array(by.concat(ax));
//                alert(cfx);return false;

                var mystring = ax.toString();
                return {
                    str: mystring.split(',').join(''),
                    rows: ax.length
                }
            },
//default
//            B = function (a,b) {
//               
//                for (var e = u(b), c = a.buttons.exportData(b.exportOptions) , d = b.fieldBoundary, h = b.fieldSeparator,
//                        f = RegExp(d, "g"), g = b.escapeChar !== k ? b.escapeChar : "\\", i = function (a) {
//                        
//                        for (var b = "", c = 0, e = a.length; c < e; c++)
//                        0 < c && (b += h), b += d ? d + ("" + a[c]).replace(f, g + d) + d : a[c];
//                    return b
//                }, n = b.header ? i(c.header) + e : "", j = b.footer ? e + i(c.footer) : "", l = [], m = 0, o = c.body.length; m < o; m++)
//                    l.push(i(c.body[m]));
//                return {
//                    str: n + l.join(e) + j,
//                    rows: l.length
//                }
//            },


            C = function () {
                return -1 !== navigator.userAgent.indexOf("Safari") && -1 === navigator.userAgent.indexOf("Chrome") && -1 === navigator.userAgent.indexOf("Opera")
            },
            m = {
                "_rels/.rels": '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">\t<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>',
                "xl/_rels/workbook.xml.rels": '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">\t<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/></Relationships>',
                "[Content_Types].xml": '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">\t<Default Extension="xml" ContentType="application/xml"/>\t<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>\t<Default Extension="jpeg" ContentType="image/jpeg"/>\t<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>\t<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/></Types>',
                "xl/workbook.xml": '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">\t<fileVersion appName="xl" lastEdited="5" lowestEdited="5" rupBuild="24816"/>\t<workbookPr showInkAnnotation="0" autoCompressPictures="0"/>\t<bookViews>\t\t<workbookView xWindow="0" yWindow="0" windowWidth="25600" windowHeight="19020" tabRatio="500"/>\t</bookViews>\t<sheets>\t\t<sheet name="Sheet1" sheetId="1" r:id="rId1"/>\t</sheets></workbook>',
                "xl/worksheets/sheet1.xml": '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006" mc:Ignorable="x14ac" xmlns:x14ac="http://schemas.microsoft.com/office/spreadsheetml/2009/9/ac">\t<sheetData>\t\t__DATA__\t</sheetData></worksheet>'
            };

// changed
    l.ext.buttons.copyHtml5 = {
        className: "buttons-copy buttons-html5",
        text: function (a) {
            return a.i18n("buttons.copy", "Copy")
        },
        //action: function (a, b, d, c) {
        action: function (a, b, d, c) {

            $pageBody = $("body");

            for (var key in b.context[0].aLastSort) {  // Get Last Sort column order and column name 
                var column = b.context[0].aLastSort[key].col;
                var order = b.context[0].aLastSort[key].dir;
            }
            var searchvalue = b.context[0].oPreviousSearch.sSearch;  // Get Last Search value 
            var exprot_data = "";
            $.ajax({
                url: ajax_object.ajaxurl,
                data: {action: 'nimble_ajax_datatable', id: getid(), searchvalue: searchvalue, exportbutton: 'true', column: column, ordertype: order},
                method: 'POST',
                async: false,
                success: function (dd) {
                    data = JSON.parse(dd);
                    var xy = new Array();
                    var yy=new Array();
                    i = 0;
                    j=0;
                    
                    
                    //alert(data.columns);return false;
                    
                    
                    $.each(data.data, function (r, value) {
                        xy[i++] = JSON.stringify(value).split(",").join("\t");
                    });
                    
                    $.each(data.columns, function (r, columns) {
                        yy[j++] = JSON.stringify(columns).split(",").join("\t");
                    });
                    
                    a = B(xy,yy,c);
                    c = a.str;
                    exprot_data = c;
                }
            });
            d = g("<div/>").css({height: 1, width: 1, overflow: "hidden", position: "fixed", top: 0, left: 0});
            c = g("<textarea readonly/>").val(exprot_data).appendTo(d);

            if (f.queryCommandSupported("copy")) {
                d.appendTo("body");
                c[0].focus();
                c[0].select();
                try {
                    f.execCommand("copy");
                    d.remove();
                    b.buttons.info(b.i18n("buttons.copyTitle", "Copy to clipboard"), b.i18n("buttons.copySuccess", {1: "Copied one row to clipboard", _: "Copied %d rows to clipboard"},
                    a.rows), 2E3);
                    return
                } catch (i) {
                }
            }
            a = g("<span>" + b.i18n("buttons.copyKeys", "Press <i>ctrl</i> or <i>âŒ˜</i> + <i>C</i> to copy the table data<br>to your system clipboard.<br><br>To cancel, click this message or press escape.") + "</span>").append(d);
            b.buttons.info(b.i18n("buttons.copyTitle", "Copy to clipboard"), a, 0);
            c[0].focus();
            c[0].select();
            var h = g(a).closest(".dt-button-info"), j = function () {
                h.off("click.buttons-copy");
                g(f).off(".buttons-copy");
                b.buttons.info(!1)
            };
            h.on("click.buttons-copy", j);
            g(f).on("keydown.buttons-copy",
                    function (a) {
                        27 === a.keyCode && j()
                    }).on("copy.buttons-copy cut.buttons-copy", function () {
                j()
            })
        },
        exportOptions: {ajax: ''},
        fieldSeparator: "\t",
        fieldBoundary: "",
        header: !0,
        footer: !1
    };

    l.ext.buttons.csvHtml5 = {
        className: "buttons-csv buttons-html5",
        available: function () {
            return d.FileReader !== k && d.Blob
        },
        text: function (a) {
            return a.i18n("buttons.csv", "CSV")
        },
        action: function (a, b, d, c) {

            c.filename="*_"+gettime();

            u(c);
            var a = '';

            for (var key in b.context[0].aLastSort) {  // Get Last Sort column order and column name 
                var column = b.context[0].aLastSort[key].col;
                var order = b.context[0].aLastSort[key].dir;
            }
            var searchvalue = b.context[0].oPreviousSearch.sSearch;  // Get Last Search value 

            b = b.buttons.exportData(c.exportOptions);
            var fh = b.header;
            if (c.header) {

                var line = '';
                $.each(fh, function (i, value) {
                    line += '"' + value + '",';
                });
                line = line.slice(0, -1);
                a += line + '\n';
            }
            //var id=document.getElementById("nimble_cf7_names").value;
            $.ajax({
                "serverSide": true,
                url: ajax_object.ajaxurl,
                //url: 'scripts/server_processing.php?searchvalue=' + searchvalue + '&column=' + column + '&ordertype=' + order,
                data: {action: 'nimble_ajax_datatable', id: getid(), searchvalue: searchvalue, exportbutton: 'true', column: column, ordertype: order},
                method: 'POST',
                sync: true
            }).success(function (dd) {
                var data = JSON.parse(dd);

                $.each(data.data, function (r, record) {
                    var line = '';
                    var array = String(record).split(",");
                    $.each(array, function (key, value) {
                        line += '"' + value + '",';
                    });
                    line = line.slice(0, -1);
                    a += line + '\n';
                });
                b = c.charset;
                !1 !== b ? (b || (b = f.characterSet || f.charset), b && (b = ";charset=" + b)) : b = "";
                j(new Blob([a], {
                    type: "text/csv" + b
                }), t(c))
            });
        },
        filename: "*",
        extension: ".csv",
        exportOptions: {},
        fieldSeparator: ",",
        fieldBoundary: '"',
        escapeChar: '"',
        charset: null,
        header: !0,
        footer: !1
    };

    var getExcelCell = function (v) {
        if ($.isNumeric(v)) {
            return '<c t="n"><v>' + v + '</v></c>';
        }
        if (v === true || v === false) {
            v = v ? '1' : '0';
            return '<c t="b"><v>' + v + '</v></c>';
        }
        if (!v) {
            v = '';
        } else {
////            v = v.replace(/&(?!amp;)/g, "&amp;");
////            v = v.replace(/[\x00-\x1F\x7F-\x9F]/g, '');
            v = v;
            v = v;
        }
        return '<c t="inlineStr"><is><t>' + v + '</t></is></c>';
    };

    l.ext.buttons.excelHtml5 = {
        className: "buttons-excel buttons-html5",
        available: function () {
            return d.FileReader !== k && d.JSZip !== k && !C()
        },
        text: function (a) {
            return a.i18n("buttons.excel", "Excel")
        },
        action: function (e, b, e, c) {  //  action: function (a, b, e, c) {


            for (var key in b.context[0].aLastSort) {  // Get Last Sort column order and column name 
                var column = b.context[0].aLastSort[key].col;
                var order = b.context[0].aLastSort[key].dir;
            }
            var searchvalue = b.context[0].oPreviousSearch.sSearch;  // Get Last Search value 

            e.prop('disabled', true);
            var cols = b.columns().indexes();
            b = b.buttons.exportData(c.exportOptions);
            var fh = b.header;
            $.ajax({
                "serverSide": true,
                url: ajax_object.ajaxurl,
                //url: 'scripts/server_processing.php?searchvalue=' + searchvalue + '&column=' + column + '&ordertype=' + order,
                data: {action: 'nimble_ajax_datatable', id: getid(), searchvalue: searchvalue, exportbutton: 'true', column: column, ordertype: order},
                method: 'POST',
                sync: true
            }).success(function (dd) {
                var data = JSON.parse(dd);

                var body = [];
                if (c.header) {

                    var p = '';
                    $.each(fh, function (i, j) {

                        p += '<c t="inlineStr"><is><t>' + j + '</t></is></c>';
                    });
                    body.push('<row>' + p + '</row>');
                }

                $.each(data.data, function (r, record) {
                    var row = [];
                    row.push('<row>');
                    var p = '';

                    var array = String(record).split(",");
                    $.each(array, function (key, value) {
                        p += '<c t="inlineStr"><is><t>' + value + '</t></is></c>';
                    });
                    row.push(p);
                    p = '';
                    row.push('</row>');
                    body.push(row.join(''));
                });


                var a = body.join('');
                c.footer && (a += e(b.footer));




                var b = new d.JSZip,
                        e = b.folder("_rels"),
                        f = b.folder("xl"),
                        h = b.folder("xl/_rels"),
                        g = b.folder("xl/worksheets");
                b.file("[Content_Types].xml",
                        m["[Content_Types].xml"]);


                e.file(".rels", m["_rels/.rels"]);
                f.file("workbook.xml", m["xl/workbook.xml"]);
                h.file("workbook.xml.rels", m["xl/_rels/workbook.xml.rels"]);
                g.file("sheet1.xml", m["xl/worksheets/sheet1.xml"].replace("__DATA__", a));
                j(b.generate({
                    type: "blob"
                }), t(c))
            }).always(function () {
                e.prop('disabled', false);
            });

        },
        filename: "*",
        extension: ".xlsx",
        exportOptions: {},
        header: !0,
        footer: !1
    };

    l.ext.buttons.pdfHtml5 = {
        className: "buttons-pdf buttons-html5",
        available: function () {
            return d.FileReader !== k && d.pdfMake
        },
        text: function (a) {
            return a.i18n("buttons.pdf",
                    "PDF")
        },
        action: function (a, b, e, c) {

            c.filename="*_"+gettime();

            u(c);
            a = b.buttons.exportData(c.exportOptions);
            for (var key in b.context[0].aLastSort) {  // Get Last Sort column order and column name 
                var column = b.context[0].aLastSort[key].col;
                var order = b.context[0].aLastSort[key].dir;
            }
            var searchvalue = b.context[0].oPreviousSearch.sSearch;  // Get Last Search value 

            b = b.buttons.exportData(c.exportOptions);
            var fh = b.header;
            b = [];

            c.header && b.push(g.map(a.header, function (a) {
                return {
                    text: "string" === typeof a ? a : a + "",
                    style: "tableHeader"
                }
            }));

            $.ajax({
                "serverSide": true,
                url: ajax_object.ajaxurl,
                //url: 'scripts/server_processing.php',
                data: {action: 'nimble_ajax_datatable', id: getid(), searchvalue: searchvalue, exportbutton: 'true', column: column, ordertype: order},
                method: 'POST',
                sync: true
            }).success(function (dd) {
                var data = JSON.parse(dd);
                var allrecord = [];
                $.each(data.data, function (r, record) {
                    allrecord.push(record);
                });

                a.body = allrecord;
                for (var f = 0, e = a.body.length; f < e; f++)
                    b.push(g.map(a.body[f], function (a) {
                        return {
                            text: "string" === typeof a ? a : a + "",
                            style: f % 2 ? "tableBodyEven" : "tableBodyOdd"
                        }
                    }));
                c.footer && b.push(g.map(a.footer, function (a) {
                    return {
                        text: "string" === typeof a ? a : a + "",
                        style: "tableFooter"
                    }
                }));
                a = {
                    pageSize: c.pageSize,
                    pageOrientation: c.orientation,
                    content: [{
                            table: {
                                headerRows: 1,
                                body: b
                            },
                            layout: "noBorders"
                        }],
                    styles: {
                        tableHeader: {
                            bold: !0,
                            fontSize: 11,
                            color: "white",
                            fillColor: "#2d4154",
                            alignment: "center"
                        },
                        tableBodyEven: {},
                        tableBodyOdd: {
                            fillColor: "#f3f3f3"
                        },
                        tableFooter: {
                            bold: !0,
                            fontSize: 11,
                            color: "white",
                            fillColor: "#2d4154"
                        },
                        title: {
                            alignment: "center",
                            fontSize: 15
                        },
                        message: {}
                    },
                    defaultStyle: {
                        fontSize: 10
                    }
                };
                c.message && a.content.unshift({
                    text: c.message,
                    style: "message",
                    margin: [0, 0, 0, 12]
                });
                c.title && a.content.unshift({
                    text: F(c, !1),
                    style: "title",
                    margin: [0, 0,
                        0, 12
                    ]
                });
                c.customize && c.customize(a);
                a = d.pdfMake.createPdf(a);
                "open" === c.download && !C() ? a.open() : a.getBuffer(function (a) {
                    a = new Blob([a], {
                        type: "application/pdf"
                    });
                    j(a, t(c))
                })
            });

        },
        title: "*",
        filename: "*",
        extension: ".pdf",
        exportOptions: {},
        orientation: "portrait",
        pageSize: "A4",
        header: !0,
        footer: !1,
        message: null,
        customize: null,
        download: "download"
    };
    return l.Buttons
});


function gettime()
{
    var date = new Date();
    var newdate = (date.getHours() - 12) + "_" + date.getMinutes() + "_" + date.getSeconds();
    //setInterval(gettime, 1000);
    return newdate;
}
