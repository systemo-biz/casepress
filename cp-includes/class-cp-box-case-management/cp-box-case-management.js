

//format data from server and render list nodes for select2
function elementFormatResult(element) {
        //alert(element.title);
        var markup = "<div id=\"select-list\">";
        //if (movie.posters !== undefined && movie.posters.thumbnail !== undefined) {
        //	markup += "<td class='movie-image'><img src='" + movie.posters.thumbnail + "'/></td>";
        //}
        markup += "<div class='node-title'>" + element.title + "</div>";
        if (element.email !== undefined) {
                markup += "<div class='node-email'>" + element.email + "</div>";
        }
        if (element.organization !== undefined) {
                markup += "<div class='node-organization'>" + element.organization + "</div>";
        }

        markup += "</div>";
        //alert(markup);
        return markup;
}

//get field for put to input 
function elementFormatSelection(element) {
        return element.title;
}
