/*
    Contact Form 7 to Database Extension
    Copyright 2010 Michael Simpson  (email : michael.d.simpson@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

/* This is a script to be used with a Google Spreadsheet to make it dynamically load data (similar to Excel IQuery)
 Instructions:
1. Create a new Google Spreadsheet
2. Go to Tools menu -> Scripts -> Script Editor...
3. Copy the text from this file and paste it into the Google script editor.
4. Save and close the script editor.
5. Click on a cell A1 in the Spreadsheet (or any cell)
6. Enter in the cell the formula: 
   =CF7ToDBData("siteUrl", "formName", "user", "pwd")
  Where the parameters are (be sure to quote them):
    siteUrl: the URL of you site, e.g. "http://www.mywordpress.com"
    formName: name of the form
    user: your login name on your wordpress site
    pwd: password
*/

function CF7ToDBData(siteUrl, formName, user, pwd) {
  return csvToArray(fetchCF7ToDBCSVText(siteUrl, formName, user, pwd));
}

function fetchCF7ToDBCSVText(siteUrl, formName, user, pwd) {
  var encformName = encodeURI(formName).replace(new RegExp("%20", "g"), "%2B");
  var url = siteUrl + "/wp-login.php?redirect_to=/wp-content/plugins/contact-form-7-to-database-extension/export.php%3Fform%3D" + encformName;
  var postData = "log=" + encodeURI(user) + "&pwd=" + encodeURI(pwd);  
  var response = UrlFetchApp.fetch(
    url,
    {
      method:"post",
      payload: postData 
    });
  return response.getContentText();
}

// Taken from: http://stackoverflow.com/questions/1293147/javascript-code-to-parse-csv-data
function csvToArray( text ) { 
  text = CSVToArray( text, "," );
  var arr = [];
  var c = [];
  for (var i=0;i < text.length-1;i++) {
    c=[];
    for (var j=0; j< text[0].length;j++){
      c.push(text[i][j]);
    }
    arr.push(c);
  }
  
  return arr;
}

// Taken from: http://stackoverflow.com/questions/1293147/javascript-code-to-parse-csv-data
// This will parse a delimited string into an array of
// arrays. The default delimiter is the comma, but this
// can be overriden in the second argument.
function CSVToArray( strData, strDelimiter ){
  // Check to see if the delimiter is defined. If not,
  // then default to comma.
  strDelimiter = (strDelimiter || ",");
  
  // Create a regular expression to parse the CSV values.
  var objPattern = new RegExp(
    (
      // Delimiters.
      "(\\" + strDelimiter + "|\\r?\\n|\\r|^)" +
      
      // Quoted fields.
      "(?:\"([^\"]*(?:\"\"[^\"]*)*)\"|" +
      
      // Standard fields.
      "([^\"\\" + strDelimiter + "\\r\\n]*))"
    ),
    "gi"
  );
  
  
  // Create an array to hold our data. Give the array
  // a default empty first row.
  var arrData = [[]];
  
  // Create an array to hold our individual pattern
  // matching groups.
  var arrMatches = null;
  
  
  // Keep looping over the regular expression matches
  // until we can no longer find a match.
  while (arrMatches = objPattern.exec( strData )){
    
    // Get the delimiter that was found.
    var strMatchedDelimiter = arrMatches[ 1 ];
    
    // Check to see if the given delimiter has a length
    // (is not the start of string) and if it matches
    // field delimiter. If id does not, then we know
    // that this delimiter is a row delimiter.
    if (
      strMatchedDelimiter.length &&
      (strMatchedDelimiter != strDelimiter)
    ){
      
      // Since we have reached a new row of data,
      // add an empty row to our data array.
      arrData.push( [] );
      
    }
    
    
    // Now that we have our delimiter out of the way,
    // let's check to see which kind of value we
    // captured (quoted or unquoted).
    if (arrMatches[ 2 ]){
      
      // We found a quoted value. When we capture
      // this value, unescape any double quotes.
      var strMatchedValue = arrMatches[ 2 ].replace(
        new RegExp( "\"\"", "g" ),
        "\""
      );
      
    } else {
      
      // We found a non-quoted value.
      var strMatchedValue = arrMatches[ 3 ];
      
    }
    
    
    // Now that we have our value string, let's add
    // it to the data array.
    arrData[ arrData.length - 1 ].push( strMatchedValue );
  }
  
  // Return the parsed data.
  return( arrData );
}

