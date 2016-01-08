var moduleid = 1;
var pageid = 1;
var uidget = 1;
var uidparam = 1;
function sitemapDeleteIt(id) {
    $('#' + id).remove();
    return true;
}
function sitemapAddElement(myvalue) {
    myvalue = myvalue || '';
    var text = '' +
        '<h2>Module Name: <label class="label-module-name"><input type="text" name="sitemap[' + moduleid + '][name]" value="' + myvalue + '" placeholder="Module name" class="form-control" required pattern="^[ёа-яa-z0-9-]+$"></label><div class="delete" onclick="sitemapDeleteIt(\'module-block-' + moduleid + '\');">УДАЛИТЬ</div></h2>' +
        '<div class="module-block-options">' +
        '<label><input type="checkbox" id="sitemap-'+moduleid+'-config" name="sitemap[' + moduleid + '][options-config]" value="1"> Special config</label>' +
        '<label><input type="checkbox" id="sitemap-'+moduleid+'-controller" name="sitemap[' + moduleid + '][options-controller]" value="1"> Special controller</label>' +
        '<label><input type="checkbox" id="sitemap-'+moduleid+'-allpages" name="sitemap[' + moduleid + '][options-allpages]" value="1"> Special allpages</label>' +
        '<label><input type="checkbox" id="sitemap-'+moduleid+'-before" name="sitemap[' + moduleid + '][options-before]" value="1"> Special before</label>' +
        '<label><input type="checkbox" id="sitemap-'+moduleid+'-after" name="sitemap[' + moduleid + '][options-after]" value="1"> Special after</label>' +
        '<label><input type="checkbox" id="sitemap-'+moduleid+'-sitemap" name="sitemap[' + moduleid + '][options-sitemap]" value="1"> Personal sitemap</label>' +
        '</div>' +
        '<h3>Pages: <button onclick="return sitemapAddPage(' + moduleid + ');" class="btn btn-primary btn-xs">Add new Page</button></h3>' +
        '<div id="sitemap-pagelist-' + moduleid + '"></div>';

    var innerDiv = document.createElement('div');
    innerDiv.className = 'module-block';
    innerDiv.id = 'module-block-' + moduleid;
    document.getElementById('sitemap-container').appendChild(innerDiv);
    innerDiv.innerHTML = text;
    ++moduleid;
    return false;
}
function sitemapAddPage(parentidmodule,myvalue) {
    myvalue = myvalue || '';
    var text = '' +
        '<h4>Page name: <label><input type="text" name="sitemap[' + parentidmodule + '][page][' + pageid + '][name]" placeholder="Page name" value="' + myvalue + '" class="form-control" required pattern="^[ёа-яa-z0-9-]+$"></label><div class="delete" onclick="sitemapDeleteIt(\'module-block-page-' + pageid + '\');">УДАЛИТЬ</div></h4>' +
        '<h5>GETS: <button onclick="return sitemapAddGet(' + parentidmodule + ',' + pageid + ')" class="btn btn-warning btn-xs">Add new Get</button></h5>' +
        '<div id="sitemap-getlist-' + pageid + '"></div>';

    var innerDiv = document.createElement('div');
    innerDiv.className = 'module-block-page';
    innerDiv.id = 'module-block-page-' + pageid;
    document.getElementById('sitemap-pagelist-' + parentidmodule).appendChild(innerDiv);
    innerDiv.innerHTML = text;

    ++pageid;
    return false;
}

function sitemapAddGet(parentidmodule,parentidpage,myvalue) {
    myvalue = myvalue || '';
    var text = '' +
        '<h4>Get name: <label><input type="text" name="sitemap[' + parentidmodule + '][page][' + parentidpage + '][get][' + uidget + '][name]" placeholder="Get name" value="' + myvalue + '" class="form-control"></label></h4>' +
        '<h5>Params: <button onclick="return sitemapAddParams(' + parentidmodule + ', ' + parentidpage + ',' + uidget + ')" class="btn btn-info btn-xs">Add new Params</button></h5>' +
        '<div id="sitemap-paramslist-' + uidget + '"></div>';

    var innerDiv = document.createElement('div');
    innerDiv.className = 'module-block-get';
    document.getElementById('sitemap-getlist-' + parentidpage).appendChild(innerDiv);
    innerDiv.innerHTML = text;

    ++uidget;
    return false;
}

function sitemapAddParams(parentidmodule,parentidpage,parentidget,mykey,myvalue) {
    mykey = mykey || '';
    myvalue = myvalue || '';
    var text = '' +
        '<label>' +
        '<select name="sitemap[' + parentidmodule + '][page][' + parentidpage + '][get][' + parentidget + '][param][' + uidparam + '][select]">' +
        '<option' + (mykey == 'none' ? ' selected' : '') + '>none</option>' +
        '<option' + (mykey == 'req' ? ' selected' : '') + '>req</option>' +
        '<option' + (mykey == 'default' ? ' selected' : '') + '>default</option>' +
        '<option' + (mykey == 'rules' ? ' selected' : '') + '>rules</option>' +
        '<option' + (mykey == 'type' ? ' selected' : '') + '>type</option>' +
        '</select>' +
        '<input type="text" name="sitemap[' + parentidmodule + '][page][' + parentidpage + '][get][' + parentidget + '][param][' + uidparam + '][input]" placeholder="Option Key" value="' + myvalue + '">' +
        '</label>';

    var innerDiv = document.createElement('div');
    innerDiv.className = 'module-block-params';
    document.getElementById('sitemap-paramslist-' + parentidget).appendChild(innerDiv);
    innerDiv.innerHTML = text;
    ++uidparam;
    return false;
}
