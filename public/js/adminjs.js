function IsJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

function hasNumber(str) {
    return /\d/.test(str);
}

function isJSONString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

function logAll(array) {
    if (!array.length) {
        console.log('Nenhum Parâmetro Informado');
    }

    for (i = 0; i < array.length; i++) {
        console.log('Index ' + i + ': ' + array[i]);
    }

}

function unitValidate(array, type) {

    response_array = [];
    response_array.return = [];
    response_array.success = true;
    if (!type) {
        for (i = 0; i < array.length; i++) {
            if (array[i] == '' || array[i] === false || !array[i]) {
                return false
            }
        }
        return true;
    }

    for (i = 0; i < array.length; i++) {
        if (array[i].value == '' || array[i].value === false) {
            tmp_obj = new Object();
            tmp_obj.field = array[i].field;
            tmp_obj.value = array[i].value;
            response_array.return.push(tmp_obj)
        }
    }

    if (response_array.return.length) {
        response_array.success = false;
    }

    return response_array;

}

function converterData(data, input_format, output_format) {
    if (!data) {
        return;
    }
    input_format = input_format || 'MYSQL';
    output_format = output_format || 'BR';

    input_format = input_format.toUpperCase();
    output_format = output_format.toUpperCase();

    if (input_format == 'MYSQL' && output_format == 'BR') {
        //1991-12-11
        tmp_ano = data[0] + data[1] + data[2] + data[3];
        tmp_mes = data[5] + data[6];
        tmp_dia = data[8] + data[9];
        return tmp_dia + "/" + tmp_mes + "/" + tmp_ano;
    } else {
        tmp_ano = data[6] + data[7] + data[8] + data[9];
        tmp_mes = data[3] + data[4];
        tmp_dia = data[0] + data[1];
        return tmp_ano + '-' + tmp_mes + '-' + tmp_dia;
    }

    return 'falta_implementar';

}

function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
}

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

function eraseCookie(name) {
    document.cookie = name + '=; Max-Age=-99999999;';
}

/**
 * Classe que gera um número randômico
 * @param entropy -- Parâmetro de Entropia (número inteiro)
 * @returns {number}
 * @constructor
 */

function RandomNumber(entropy) {

    if (!entropy) {
        return Math.floor(Math.random() * 100000000);
    }

    return Math.floor(Math.random() * entropy);


}

/**
 * Função que Decodifica a String HTML.
 * @param encodedStr
 * @returns {string}
 * @constructor
 */
function HtmlDecode(encodedStr) {
    var parser = new DOMParser;
    var dom = parser.parseFromString(
        '<!doctype html><body>' + encodedStr,
        'text/html');
    var decodedString = dom.body.textContent;
    var decodedString = dom.body.textContent;
    return decodedString;
}

/**
 * Função que similar à "LPAD" do MySQL
 * @param n
 * @param width
 * @param z
 * @returns {*}
 */
function pad(n, width, z) {
    z = z || '0';
    n = n + '';
    return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
}


/**
 * Number.prototype.format(n, x, s, c)
 * Extende as funcionalidades do objeto "Number"
 * @param integer n: Tamanho do Decimal
 * @param integer x: Tamanho do segmento completo
 * @param mixed   s: Delimitador de Segmento
 * @param mixed   c: Delimitador Decimal
 */
Number.prototype.format = function (n, x, s, c) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
        num = this.toFixed(Math.max(0, ~~n));

    return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
};


/**
 * Função que Converte Números par ao padrão MYSQL
 * @param options
 * @returns {XML|string|*|void}
 */
function mysqlFormat(number) {

    if (number == null) {
        return number;
    }

    var args = Array.prototype.slice.call(arguments, 1);

    tmp = number;
    tmp = tmp.replace('.', '');
    tmp = tmp.replace(',', '.');

    return tmp;

}

/**
 * Converte o formato MySQL para o formato numérico Brasileiro
 * @param number
 * @param decimal_places
 * @param zero_if_null
 * @returns {formatted_number}
 */
function realFormat(number, decimal_places, zero_if_null) {

    decimal_places = decimal_places || 2;

    if (number == null || number == '') {

        if (zero_if_null) {
            return '0,00';
        }

        //return realFormat(number,2);
    }

    if (!decimal_places) {
        return parseFloat(number).toFixed(2).replace('.', ',').replace(/(\d)(?=(\d{3})+\,)/g, "$1.");
    }


    return parseFloat(number).toFixed(decimal_places).replace('.', ',').replace(/(\d)(?=(\d{3})+\,)/g, "$1.");


}


/**W2UI FUNCTIONS*/

/**
 * Função que mescla as mudanças do grid (quanto editável)
 * @param {w2uigrid} grid
 * @returns {records}
 */
function mergeData(grid) {

    var args = Array.prototype.slice.call(arguments, 1);

    older = grid.records;
    newer = grid.getChanges();

    if (newer.length == 0) {
        return older;
    }

    //Loop para cada registro do grid
    for (i = 0; i < older.length; i++) {

        //Loop para emparelhar os valores
        for (j = 0; j < newer.length; j++) {

            //Condição para emparelhamento de OLDER = NEWER
            if (older[i].recid == newer[j].recid) {

                //Loop para checagem dos argumentos
                for (k = 0; k < args.length; k++) {
                    //Checa se o objeto existe em NEWER
                    if (newer[j].hasOwnProperty(args[k])) {

                        //console.log('OLD: '+older[i][args[k]]+' NEW: '+newer[i][args[k]]);
                        //sobrescreve os valores NEWER -> OLDER
                        older[i][args[k]] = newer[j][args[k]];

                    }
                }

            }
        }

    }//Fim do Primeiro Loop

    return older;

}


/**FIM DAS FUNÇÕES W2UI*/

/*CARLOS CUSTOM FUNCTION*
 * Funções customizadas para JQUERY
 */

/**
 * Função que mascara uma Div.
 * Para mascarar: $('#my-div-to-mask').overlayMask();
 * Para remover a máscara: $('#my-div-to-mask').overlayMask('hide');
 * @param {type} $
 * @returns {undefined}
 */
(function ($) {
    $.fn.overlayMask = function (action) {
        var mask = this.find('.overlay-mask');

        //Cria a Máscara Requerida

        if (!mask.length) {
            this.css({
                position: 'relative'
            });
            mask = $('<div class="overlay-mask"></div>');

            mask.css({
                position: 'absolute',
                width: '100%',
                height: '100%',
                top: '0px',
                left: '0px',
                zIndex: 50000
            }).appendTo(this);
        }
        //CSS adicional abaixo para filtros de Opacidade

        mask.css("-ms-filter", "progid:DXImageTransform.Microsoft.Alpha(Opacity=50)");
        /* IE 8 */
        mask.css("filter", " alpha(opacity=50)");
        /* IE 5-7 */
        mask.css("-moz-opacity", "0.5");
        /* Netscape */
        mask.css("-khtml-opacity", " 0.5");
        /* Safari 1.x */
        mask.css("opacity", " 0.5");
        /* Bons Navegadores*/

        //Adiciona a cor de Fundo
        mask.css("background-color", "black");

        //Age de acordo com os parâmetros

        if (!action || action === 'show') {
            mask.show();
        } else if (action === 'hide') {
            mask.hide();
        }

        return this;
    };
})(jQuery);


/*FIM DAS MINHAS FUNÇÕES CUSTOMIZADAS (CARLOS)*/

function setEventsAfterAjax() {
    jQuery('.modalcaller').on('click', function (e) {
        e.preventDefault();
        var url = jQuery(this).attr('href');
        loadModalItemHtml(url);
    });

    jQuery('.vinipagination').on('click', function (e) {
        e.preventDefault();
        var pg = jQuery(this).attr('data-page');
        clearContentAjaxCall('.vinipagination', {page: pg});
    });

    jQuery('.ajaxgetlink').on('click', function (e) {
        e.preventDefault();
        var contId = jQuery(this).data('containerid');
        var dbgerror = jQuery(this).attr('data-debug');
        var jso = jQuery(this).data('json');//jQuery(this).attr('data-json');
        var url = jQuery(this).attr('href');
        // console.log(JSON.parse('{"filename":"teste"}'));
        clearContentIdAjaxCall(dbgerror, url, contId, jso);
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
}

function doAjaxCall(aMethod, aUrl, aData, aProcSucc, aProcFail, type, ajaxParams) {

    ajaxParams = ajaxParams || {};
    preventCustomEvents = (typeof(ajaxParams.preventCustomEvents) != 'undefined') ? ajaxParams.preventCustomEvents : 0;
    jQuery.ajax({
        url: aUrl,
        method: aMethod,
        data: aData,
        preventCustomEvents: preventCustomEvents,
        dataType: (typeof type == 'undefined') ? "json" : type,
        beforeSend: function () {
            $('#ajxloader').css('display', 'inline');
        },
        complete: function () {
            $('#ajxloader').css('display', 'none');
            setEventsAfterAjax();
        },
        success: function (reponse) {
            aProcSucc(reponse);
        },
        error: function (response) {

            //if (aMethod.toUpperCase() == 'POST'){
            if (['POST','PUT','PATCH','DELETE'].includes(aMethod.toUpperCase())){
                //if (response.status == 422) {
                if (response.status >= 400 && response.status <= 499) {


                    const is_json = isJSONString(response.responseText);

                    if (!is_json){
                        return;
                        //msg(response.responseText,false,'log');
                        //return;
                    }


                    var error = $.parseJSON(response.responseText);
                    if (Object.prototype.toString.call(error.errors) == "[object Object]")
                        error = error.errors;
                    error_msg = '<b>A operação não pôde ser realizada porque: </b><br><br>';

                    for (var key in error) {
                        if (error[key] == false)
                            continue;
                        error_msg += '<span style="color: indianred;"><b>*</b></span>' + error[key] + '<br>';

                    }
                    msg(error_msg, false, 'error');
                    return;
                }

                if (response.status == 500) {
                    error_msg = '<b>A operação não pôde ser realizada porque: </b><br><br>' + response.responseText;
                    msg(error_msg, false, 'log');
                    return;
                }

            }

            aProcFail(response);

        }
    });
}

function doGetAjaxCall(aUrl, aData, aProcSucc, aProcFail, type) {
    doAjaxCall('GET', aUrl, aData, aProcSucc, aProcFail, type);
}

function doPostAjaxCall(aUrl, aData, aProcSucc, aProcFail, type, ajaxParams) {
    doAjaxCall('POST', aUrl, aData, aProcSucc, aProcFail, type, ajaxParams);
}

function clearContentAjaxCall(aErrorDebugId, aData) {
    var url = jQuery(this).attr('href');
    doGetAjaxCall(
        url,
        aData,
        function (data) {
            // console.log(data);
            $('#admincontentrow').html(data.html.admin_content);
        },
        function (data) {
            console.error(aErrorDebugId + ' | ' + data);
        }
    );
}

function clearContentIdAjaxCall(aErrorDebugId, aUrl, aContainerId, aData) {
    doGetAjaxCall(
        aUrl,
        aData,
        function (data) {
            // console.log(data);
            $('#' + aContainerId).html(data.html);
        },
        function (data) {
            console.error(aErrorDebugId + ' | ' + data);
        }
    );
}


function loadModalItemHtml(url) {
    var modalContainer = jQuery('#modalcontainer_');
    var modalLabel = jQuery('#myModalLabel');
    jQuery.ajax({
        url: url,
        success: function (data) {
            // console.log(data);
            modalLabel.html(data.label);
            modalContainer.html(data.form);//.attr('data-item-number', itemNumber);
            $('#myModal').modal();
            // $galleryContainer.stop().animate({top: 0}, 1000, 'easeOutExpo');
        },
        // error: function(msg){
        //     console.error('loadItemHtml: '+msg);
        // }
        statusCode: {
            404: function (data) {
                alert("Erro ao carregar modal: " + data);
            }
        }
    });
}

function createTreeViewRH(aId, aOrgao, aUnidade, aDepartamento, aFicha) {
    var treeView = [];
    var listDepartamentosChecked = [];
    var listFichasChecked = [];
    var listUnidadesChecked = [];
    var listOrgaoChecked = [];
    $.each(aOrgao, function (index, element) {
        var item = {
            item: {

                id: "OG" + element.id,
                label: pad(element.num_orgao, 3) + " - " + element.nome,
                value: element.id,
                checked: true,
                val: element.id,
                name: 'Orgao'
            }
        };
        item.children = [];
        var orgao_id = element.id;
        var unidades = aUnidade.filter(function (elem, i, array) {
            return parseInt(elem.orgao_id) === parseInt(orgao_id);
        });
        listOrgaoChecked.push('OG' + element.id);
        $.each(unidades, function (index, element) {
            var item2 = {
                item: {

                    id: "UN" + element.id,
                    label: pad(element.num_unidade, 3) + " - " + element.nome,
                    value: element.id,
                    checked: true,
                    val: element.id,
                    name: 'Unidade'
                }
            };
            item2.children = [];
            listUnidadesChecked.push('UN' + element.id);
            var unidade_id = element.id;
            var departamentos = aDepartamento.filter(function (elem, i, array) {
                return parseInt(elem.unidade_id) === parseInt(unidade_id);
            });
            var itemF = {
                item: {
                    id: 99999 + "FH",
                    label: 'Fichas',
                    value: 99999,
                    checked: true,
                    val: 9999,
                    name: 'Ficha'
                }
            };
            var item3 = {
                item: {
                    id: 9999 + "DP",
                    label: 'Departamentos',
                    value: 9999,
                    checked: true,
                    val: 999,
                    name: 'Departamento'
                }
            };
            item3.children = [];
            itemF.children = [];
            $.each(departamentos, function (index, element) {
                var item4 = {
                    item: {
                        id: "DP" + element.id,
                        label: pad(element.num_departamento, 3) + " - " + element.nome,
                        value: element.id,
                        checked: true,
                        val: element.id,
                        name: 'Departamento'
                    }
                };
                item3.children.push(item4);
                listDepartamentosChecked.push('DP' + element.id);
            });
            var fichas = aFicha.filter(function (elem, i, array) {
                return parseInt(elem.unidade_id) === parseInt(unidade_id);
            });
            $.each(fichas, function (index, element) {
                var item4 = {
                    item: {
                        id: "FH" + element.id,
                        label: pad(element.ficha, 4) + " - Elemento - " + element.nr_elem + " - Fonte: " + element.fonte_id,
                        value: element.id,
                        checked: true,
                        val: element.id,
                        name: 'Ficha'
                    }
                };
                itemF.children.push(item4);
                listFichasChecked.push('FH' + element.id);
            });
            item2.children.push(item3);
            item2.children.push(itemF);
            item.children.push(item2);
        });
        treeView.push(item);

    });
    var treeViewID = '#' + aId;
    var treeViewObject = {
        createTreeView: function () {
            $(treeViewID).checkTree({
                data: treeView,
                onCheck: function (a) {
                    var y = a["0"].attributes["0"].nodeValue.substr(0, 2);
                    var yy = a["0"].attributes["0"].nodeValue;
                    if (y === 'FH') {
                        var indexOf = listFichasChecked.indexOf(a["0"].attributes["0"].nodeValue);
                        if (indexOf === -1) {
                            listFichasChecked.push(yy);
                        }
                    }
                    if (y === 'DP') {
                        var indexOf = listFichasChecked.indexOf(a["0"].attributes["0"].nodeValue);
                        if (indexOf === -1) {
                            listDepartamentosChecked.push(yy);
                        }
                    }

                    if (y === 'UN') {
                        var indexOf = listUnidadesChecked.indexOf(a["0"].attributes["0"].nodeValue);
                        if (indexOf === -1) {
                            listUnidadesChecked.push(yy);
                        }
                    }

                    if (y === 'OG') {
                        var indexOf = listOrgaoChecked.indexOf(a["0"].attributes["0"].nodeValue);
                        if (indexOf === -1) {
                            listOrgaoChecked.push(yy);
                        }
                    }
                },
                onUnCheck: function (b) {
                    var y = b["0"].attributes["0"].nodeValue.substr(0, 2);
                    console.log(y);
                    if (y === 'FH') {
                        var indexOf = listFichasChecked.indexOf(b["0"].attributes["0"].nodeValue);
                        if (indexOf !== -1) {
                            listFichasChecked.splice(indexOf, 1);
                        }
                    }
                    if (y === 'DP') {
                        var indexOf = listDepartamentosChecked.indexOf(b["0"].attributes["0"].nodeValue);
                        if (indexOf !== -1) {
                            listDepartamentosChecked.splice(indexOf, 1);
                        }
                    }
                    if (y === 'OG') {
                        var indexOf = listOrgaoChecked.indexOf(b["0"].attributes["0"].nodeValue);
                        if (indexOf !== -1) {
                            listOrgaoChecked.splice(indexOf, 1);
                        }
                    }
                    if (y === 'UN') {
                        var indexOf = listUnidadesChecked.indexOf(b["0"].attributes["0"].nodeValue);
                        if (indexOf !== -1) {
                            listUnidadesChecked.splice(indexOf, 1);
                        }
                    }

                }
            });
        },
        getDepartamentos: function () {
            return listDepartamentosChecked;
        },
        getFichas: function () {
            return listFichasChecked;
        },
        getOrgao: function () {
            return listOrgaoChecked;
        },
        getUnidade: function () {
            return listUnidadesChecked;
        }
    };
    treeViewObject.createTreeView();
    return treeViewObject;

}

function createTypeHead(aTypeHeadId, aFuncAfterSetKey, aFuncNotFound) {

    var vTypeHeadId = '#' + aTypeHeadId;
    var vHiddenId = vTypeHeadId + '_key';

    var vJq = $(vTypeHeadId);
    var vSearch = vJq.data('search');
    var vDisplay = vJq.data('display');
    var vKeyField = vJq.data('key');
    var vHiddenName = vJq.data('hidden');
    var vHiddenValue = vJq.data('hiddenval');
    var vDisplayValue = vJq.data('displayval');

    if (vSearch == '') {
        console.error('Não foi encontrado valor para o atributo: data-search no typehead: ' + vTypeHeadId);
    }

    if (vDisplay == '') {
        console.error('Não foi encontrado valor para o atributo: data-display no typehead: ' + vTypeHeadId);
    }

    if (vKeyField == '') {
        console.error('Não foi encontrado valor para o atributo: data-key no typehead: ' + vTypeHeadId);
    }

    if (vHiddenName == '') {
        console.error('Não foi encontrado o atributo: data-hidden no typehead: ' + vTypeHeadId);
    }

    if ($(vHiddenId).length == 0) {
        if (vHiddenValue !== undefined) {
            vJq.after('<input id="' + aTypeHeadId + '_key" name="' + vHiddenName + '" type="hidden" data-display="' + vDisplayValue + '" value="' + vHiddenValue + '">');
        } else {
            vJq.after('<input id="' + aTypeHeadId + '_key" name="' + vHiddenName + '" data-display="inicial" type="hidden">');
        }
    }

    var bestPictures = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace(vDisplay),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: vSearch,
            replace: function (url, query) {
                var params = vJq.data('params');

                if (params) {
                    params = params.replace(/\s/g, '').replace(/['"]/g, '').replace(/:/g, '":"').replace(/,/g, '","');
                    var j = JSON.parse('{"' + params + '"}');
                    var ext_params = '';

                    $.each(j, function (i, val) {

                        if (val.indexOf('$') != -1)
                            ext_params += '/' + val.replace(/[$]/g, '');
                        else if ($('#' + val).val().length > 0)
                            ext_params += '/' + $('#' + val).val();
                        else
                            ext_params += '/""';
                    });

                    return url + '/' + query + ext_params;
                }
                return url + '/' + query;
            },
            wildcard: '%QUERY',
            cache: false
        },
        cache: false
    });

    var ifEmpty = '';
    if (typeof $(vTypeHeadId.trim()).data("new") != 'undefined')
        ifEmpty = '<script>' +
            'function newRegistry(selector) { ' +
            'var onNewClick = eval("onNewClick = "+$(selector.trim()).data("new")); ' +
            'if(typeof onNewClick == "function") ' +
            'onNewClick()' +
            '}' +
            '</script> ' +
            '<div style="text-align: center; padding: 5px;">' +
            '<a href="#" onclick="newRegistry(\'' + vTypeHeadId + '\');"  class="btn btn-default btn-block">Cadastrar Novo</a> ' +
            '</div>';


    function getSelected(obj, datum) {
        $(vHiddenId).val(datum[vKeyField]);
        $(vHiddenId).data('display', datum[vDisplay]);
        if (typeof aFuncAfterSetKey === "function") {
            aFuncAfterSetKey(datum);
        }
    };

    vJq.typeahead({
        highlight: true
    }, {
        name: 'best-pictures',
        display: vDisplay,
        source: bestPictures,
        limit: 50,
        tabAutocomplete: true,
        templates: {
            empty: [
                ifEmpty
            ]
        }
    }).bind("typeahead:select", function (obj, datum) {
        if (datum.hasOwnProperty(vKeyField)) {
            getSelected(obj, datum);
        } else {
            console.error('Não foi encontrado a propriedade Key: ' + vKeyField + ' para o typehead: ' + vTypeHeadId);
        }
    }).bind("typeahead:idle", function (obj, datum) {
        obj.preventDefault();

        if (($('.tt-suggestion').length === 0) && (vJq.val() != '')) {
            if (typeof aFuncNotFound === 'function') {
                aFuncNotFound(obj, datum);
            }
        }
    }).bind('typeahead:autocomplete', function (ev, suggestion) {
        getSelected(ev, suggestion);
        // console.log(ev);
        // console.log(suggestion);
    }).bind('typeahead:change', function (ev, text) {
        if ($(vHiddenId).data('display') != text) {
            $(this).typeahead('val', '');
            getSelected(ev, []);
        }
    });

    if (vDisplayValue !== undefined) {
        $(vTypeHeadId).typeahead('val', vDisplayValue);
    }
    if ($(vTypeHeadId).val().length > 0) {
        $(vTypeHeadId).typeahead('val', $(vTypeHeadId).val());
    }
}

function createTypeHeadId(aTypeHeadId) { // couldn't do the overload
    return createTypeHead(aTypeHeadId, '', '');
}

function nextFocus() {
    $('input').each(function () {
        if ($(this).val() == '' && !$(this).attr('readonly')) {
            this.focus();
            return false;
        }
    });
}

function tabRemove() {
    $('a').attr('tabindex', -1);
    $('input').filter(function () {
        return $(this).attr('readonly') ? $(this) : null;
    }).attr('tabindex', -1);
}

jQuery(document).ready(function () {
    setEventsAfterAjax();
    tabRemove();
    // initDataTable();
});


function getView(url, data, cb) {
    doPostAjaxCall(url, data,

        function (view) {

            var response = [];
            if (view.indexOf('"message":') >= 0)
                response = $.parseJSON(view);

            if (view.indexOf('"funcao":') >= 0)
                response = $.parseJSON(view);
            if (typeof response.funcao != 'undefined') {

                if (typeof response.param_func != 'undefined') {
                    window[response.funcao].apply(null, response.param_func);
                }
                else {
                    window[response.funcao]();
                }

                return;
            }
            if (typeof response.log != 'undefined') {

            }

            if (typeof response.error != 'undefined') {
                console.error(response.error);
                var error = null;
                error_msg = '<b>A operação não pôde ser realizada porque: </b><br>';
                if (Object.prototype.toString.call(response.error) == "[object Object]") {
                    error = response.error
                } else {
                    error = $.parseJSON(response.error);
                    console.log(error);
                }

                for (var key in error) {

                    error_msg += '<div color="red"><b>*</b></div>' + error[key] + '<br>';

                    /*$('#' + key + '_error').text(error[key]).show();

                    $('input[name='+key).one('blur input', function() {
                        $('#' + $(this).attr('name') + '_error').hide();
                    });
                    $('select[name='+key).change(function () {
                        $('#'+ $(this).attr('name') + '_error').hide();
                    });
                    $('textarea[name='+key).blur(function () {
                        $('#'+ $(this).attr('name') + '_error').hide();
                    });*/


                }

                msg(error_msg, false, 'error');
                return;
            }


            //Alteração para Alertas w2ui
            //Não altera o comportamento das mensagens flash
            if (typeof response.success != 'undefined') {

                if (typeof response.message != 'undefined') {
                    msg(response.message, response.success, response.type);

                } else {
                    if (!response.success) {
                        msg('Não foi possível realizar a operação', false);

                    } else {
                        msg('Operação realizada com sucesso!', true);

                    }
                }

            } else {

                if (typeof response.message != 'undefined') {

                    if (typeof response.type != 'undefined') {
                        msg(response.message, false, response.type);
                    } else {
                        msg(response.message);
                    }


                }
            }


            if (typeof response.redirect != 'undefined') {
                executeView(response.redirect);
                return;
            }

            if (typeof response.message == 'undefined') {
                executeView(view);
            }

            $('input').attr('autocomplete', 'off');
            $('div[id*="_error"]').hide();
        },
        function (json) {
            //carlos
            return;
            if (json.status == 422) {
                return;
                var error = $.parseJSON(json.responseText);
                if (Object.prototype.toString.call(error.errors) == "[object Object]")
                    error = error.errors;
                error_msg = '<b>A operação não pôde ser realizada porque: </b><br><br>';

                for (var key in error) {
                    /*$('#' + key + '_error').text(error[key]).show();

                    $('input[name='+key).one('blur input', function() {
                        $('#' + $(this).attr('name') + '_error').hide();
                    });
                    $('select[name='+key).change(function () {
                        $('#'+ $(this).attr('name') + '_error').hide();
                    });
                    $('textarea[name='+key).blur(function () {
                        $('#'+ $(this).attr('name') + '_error').hide();
                    });*/

                    //console.log(error[key]);

                    error_msg += '<span style="color: indianred;"><b>*</b></span>' + error[key] + '<br>';

                }
                msg(error_msg, false, 'error');
                return;
            }

            if (json.status == 500) {

                error_msg = '<b>A operação não pôde ser realizada porque: </b><br><br>' + json.responseText;
                msg(error_msg, false, 'log');
                /*

                var error = $.parseJSON(json.responseText);

                if (Object.prototype.toString.call(error.error) == "[object Object]")
                    error = error.error;*/

                return;
            }

            // $('body').html(json.responseText);
        }, 'html');
}

function executeView(view) {
    $('#contentView').html(view);

    eventExecuteView();
    setRequiredFields();
    makeResponsive();
    // initDataTable();
}

function eventExecuteView() {
    tabRemove();

    //*REMOVE A AÇÃO SUBMIT DE TODOS OS FORMULÁRIOS*/
    $('form').submit(function () {
        if (typeof(event) != 'undefined') {
            event.preventDefault();
        }

        //event.stopImmediatePropagation();

    });


    $('input[type="text"]').keypress(function (evt) {
        var keycode = evt.charCode || evt.keyCode;
        if (keycode === 59) {
            return false;
        }
    });
    $('textarea').keypress(function (evt) {
        var keycode = evt.charCode || evt.keyCode;
        if (keycode === 59) {
            return false;
        }
    });
    $("form").bind("keydown", function (e) {
        if (e.keyCode === 13) return false;
    });
    //$('input[type="text"]').addClass('input-sm');
    $('.viaAjaxPost').on('click', function (event) {
        event.preventDefault();
        event.stopImmediatePropagation();
        var url = '';
        var data = [];
        if ($(this).hasClass('modalSubmit'))
            $(this).parents('.modal').modal('hide');

        if ($(this).is('a')) {
            url = $(this).attr('href');
            if (url && url != '#') {
                $.sidr('close');
            }

        }
        else {
            var form = $(this).parents().filter('form');
            url = form.attr('action');
            data = form.serialize();
        }
        getView(url, data);
    });
}

function setRequiredFields() {

    $('form').addClass('form-group');

    $('label').each(function () {

        text = $(this).text();

        if (!text.length) {
            return;
        }


        last_char = text[text.length - 1];
        text = text.slice(0, -1);

        if (last_char == '*') {

            text += '<span style="font-weight: bold !important; color: red !important;">*</span>';
            $(this).html(text);
        }


    });
}

function makeResponsive() {


    /*$('.w2ui-grid-header').addClass('float-left text-uppercase text-white ');*/


    /*$("[id$='searchOverlay']").each(function(){

        $(this).addClass('w-100');



    });*/

    /*$(".step-anchor").each(function(){

        $(this).addClass('bg-tertiary');




    });*/


}

/*function makeFormPattern(){


    $(".ct-card").each(function(){

        $(this).addClass('card bg-light group-fields pl-md-0 pr-md-0 p-0 m-0');

    });

    $(".ct-heading").each(function(){

        $(this).addClass('bg-secondary text-uppercase text-white pl-0 pr-0');
    });


}*/

function setMaskOnAjaxPost() {




    $(document).ajaxSend(function (evt, xhr, settings) {
        type = settings.type.toUpperCase();

        if (['POST','PUT','PATCH','DELETE'].includes(type)) {
            if (settings.preventCustomEvents) {
                return;
            }
            mask();
        }
    });

    $(document).ajaxComplete(function (evt, xhr, settings) {
        type = settings.type.toUpperCase();
        //if (type == 'POST') {
        if (['POST','PUT','PATCH','DELETE'].includes(type)) {
            if (settings.preventCustomEvents) {
                return;
            }
            unmask();
        }

    });

    $(document).ajaxError(function (evt, xhr, settings) {
        type = settings.type.toUpperCase();
        //if (type == 'POST') {
        if (['POST','PUT','PATCH','DELETE'].includes(type)) {
            if (settings.preventCustomEvents) {
                return;
            }
            unmask();
        }
    });

}

function mask(div) {

    //console.log('Tamanho Documento MASK'+$( document ).height());

    if (!div || typeof(div) == 'undefined') {
        div = 'mask';
    }

    if (div == 'mask') {
        $("#" + div).attr('style', '');
    }

    unmask(div);
    $("#" + div).removeClass('d-none').addClass('d-flex');
    // console.log($("#"+div).hasClass('d-flex'));
    return;
}

function unmask(div) {
    //console.log('Tamanho Documento UNMASK'+$( document ).height());


    if (!div) {
        div = 'mask';
    }

    $("#" + div).removeClass('d-flex').addClass('d-none');
    return;
}


/*return new Date(value).toLocaleString('pt-BR', {year: 'numeric',
 month: 'numeric', day: 'numeric'});*/

function createGrid(name, urlData, linkVisualize, linkEdit, linkDelete, messageDelete, header) {

    header.push(
        {
            type: "control", width: '30px', searchModeButtonTooltip: "Pesquisa", editButton: false,
            deleteButton: false, searchButtonTooltip: "Filtrar", clearFilterButtonTooltip: "Limpar Filtros",
            itemTemplate: function (value, item) {
                var _str = [linkVisualize, linkEdit, linkDelete, messageDelete];

                for (var k = 0; k < _str.length; k++) {
                    var a, b;
                    if (_str[k].indexOf('[[') >= 0) {
                        a = '[[';
                        b = ']]';
                    } else {
                        a = '%5B%5B';
                        b = '%5D%5D';
                    }
                    while (_str[k].indexOf(a) >= 0) {
                        var i = _str[k].indexOf(a);
                        var j = _str[k].indexOf(b, i);
                        var key = _str[k].substring(i + a.length, j);
                        _str[k] = _str[k].replace(a + key + b, item[key]);
                    }
                }

                return '<a href="' + _str[0] + '" class="viaAjaxPost" style="margin-right: 5px" title="Visualizar"> <span class="glyphicon glyphicon-eye-open"></span></a> ' +
                    '<a href="' + _str[1] + '" class="viaAjaxPost" style="margin-right: 5px" title="Editar"><span class="glyphicon glyphicon-edit"></span></a> ' +
                    '<a href="#" onclick="BootstrapDialog.show({ ' +
                    '    title: \'Confirmação\', ' +
                    '    message: \'' + _str[3] + '\', ' +
                    '    closeByBackdrop: false, ' +
                    '    draggable: true, ' +
                    '    closeByKeyboard: true, ' +
                    '    buttons: [{ ' +
                    '        label: \'Não\', ' +
                    '        cssClass: \'btn-default\', ' +
                    '        action: function(dialog){dialog.close()} ' +
                    '}, ' +
                    '    {  ' +
                    '        label: \'Sim\', ' +
                    '        cssClass: \'btn-primary\', ' +
                    '        action: function(dialog){ ' +
                    '                   doPostAjaxCall(\'' + _str[2] + '\', null, function(response){' +
                    '   $(\'.bottom-right\').notif(response).show()}, function(){' +
                    '   $(\'.bottom-right\').notif({message: \'Houve um erro ao deletar o registro\', type: \'danger\'}).show()}); ' +
                    '                   $(\'#' + name + '\').jsGrid(\'loadData\'); ' +
                    '                   dialog.close(); ' +
                    '                } ' +
                    '    }] ' +
                    ' })" title="Excluir" style="margin-right: 5px"><span class="glyphicon glyphicon-trash"></span></a>';
            }
        });

    var MoneyField = function MoneyField(config) {
        jsGrid.Field.call(this, config);
    };

    MoneyField.prototype = new jsGrid.NumberField({

        itemTemplate: function (value) {
            // console.log(value);
            // return value.toLocaleString('de-DE')
            return 'R$ ' + value;
            // return "$" + value.toFixed(2);
        },

        filterValue: function () {
            return parseFloat(this.filterControl.val() || 0);
        },

        insertValue: function () {
            return parseFloat(this.insertControl.val() || 0);
        },

        editValue: function () {
            return parseFloat(this.editControl.val() || 0);
        }

    });

    var MyDateTimeField = function (config) {
        jsGrid.Field.call(this, config);
    };

    MyDateTimeField.prototype = new jsGrid.Field({

        css: "datetime-field",
        align: "center",


        sorter: function (date1, date2) {
            return new Date(date1) - new Date(date2);
        },

        itemTemplate: function (value) {
            if (value == undefined)
                return '';
            var date = value.split('-');
            var dateTime = date[2].split(' ');

            var a = dateTime[0] + "/" + date[1] + "/" + date[0] + " " + dateTime[1];

            if (a === '31/12/1969 00:00:00')
                return "";
            else
                return dateTime[0] + "/" + date[1] + "/" + date[0] + " " + dateTime[1];
        },

        filterTemplate: function () {
            return '<input type="text" id="filter-datetime">' +
                '<script>$("#filter-datetime").datepicker({ ' +
                '    language: "pt-BR", ' +
                '        autoclose: true, ' +
                '        todayHighlight: true ' +
                '});</script>';
        },
        filterValue: function () {
            return $('#filter-datetime').datepicker("getDate") != undefined ? $('#filter-datetime').datepicker("getDate").toISOString() : '';
        }
    });


    var MyDateField = function (config) {
        jsGrid.Field.call(this, config);
    };

    MyDateField.prototype = new jsGrid.Field({

        css: "date-field",            // redefine general property 'css'
        align: "center",              // redefine general property 'align'


        sorter: function (date1, date2) {
            return new Date(date1) - new Date(date2);
        },

        itemTemplate: function (value) {
            if (value == undefined)
                return '';
            var date = value.split('-');
            var a = date[2] + "/" + date[1] + "/" + date[0];
            if (a == '31/12/1969')
                return "";
            else
                return date[2] + "/" + date[1] + "/" + date[0];
        },

        filterTemplate: function () {
            return '<input type="text" id="filter-date">' +
                '<script>$("#filter-date").datepicker({ ' +
                '    language: "pt-BR", ' +
                '        autoclose: true, ' +
                '        todayHighlight: true ' +
                '});</script>';
        },
        filterValue: function () {
            return $('#filter-date').datepicker("getDate") != undefined ? $('#filter-date').datepicker("getDate").toISOString() : '';
        }
    });

    var MyDateTimeField = function (config) {
        jsGrid.Field.call(this, config);
    };

    MyDateTimeField.prototype = new jsGrid.Field({

        css: "datetime-field",
        align: "center",


        sorter: function (date1, date2) {
            return new Date(date1) - new Date(date2);
        },

        itemTemplate: function (value) {
            if (value == undefined)
                return '';
            var date = value.split('-');
            var dateTime = date[2].split(' ');

            var a = dateTime[0] + "/" + date[1] + "/" + date[0] + " " + dateTime[1];

            if (a == '31/12/1969 00:00:00')
                return "";
            else
                return dateTime[0] + "/" + date[1] + "/" + date[0] + " " + dateTime[1];
        },

        filterTemplate: function () {
            return '<input type="text" id="filter-datetime">' +
                '<script>$("#filter-datetime").datepicker({ ' +
                '    language: "pt-BR", ' +
                '        autoclose: true, ' +
                '        todayHighlight: true ' +
                '});</script>';
        },
        filterValue: function () {
            return $('#filter-datetime').datepicker("getDate") != undefined ? $('#filter-datetime').datepicker("getDate").toISOString() : '';
        }
    });

    var MySelectSituacaoRequisicaoField = function (config) {
        jsGrid.Field.call(this, config);
    };
    MySelectSituacaoRequisicaoField.prototype = new jsGrid.Field({
        filterValue: function () {
            return $('#select_nota').val();
        },
        filterTemplate: function () {
            return '<select id="select_nota"> <option value="">Todas</option> <option value="Requisitado">Requisitado</option>  <option value="Aprovada">Aprovada</option> <option value="Efetivado">Efetivado</option></select>';
        },
        itemTemplate: function (value) {
            return value;
        }
    });
    var MyDocField = function (config) {
        jsGrid.Field.call(this, config);
    };
    MyDocField.prototype = new jsGrid.Field({
        filterValue: function () {
            return $('#filter-doc').inputmask('unmaskedvalue');
        },
        filterTemplate: function () {
            return '<input type="text" id="filter-doc"><script>$("#filter-doc").inputmask({mask: ["999.999.999-99", "99.999.999/9999-99"], keepStatic: true, showMaskOnHover: false });</script>'
        },
        itemTemplate: function (value) {
            if (value.length == 11) {
                return VMasker.toPattern(value, "999.999.999-99");
            }
            return VMasker.toPattern(value, "99.999.999/9999-99");
        }
    });


    var MySelectExigenciaField = function (config) {
        jsGrid.Field.call(this, config);
    };
    MySelectExigenciaField.prototype = new jsGrid.Field({
        filterValue: function () {
            return $('#select_nota').val();
        },
        filterTemplate: function () {
            return '<select id="select_nota"> <option value="">Todas</option> <option value="E">Econômico</option>  <option value="I">Imóvel</option></select>';
        },
        itemTemplate: function (value) {
            return value;
        }
    });

    jsGrid.fields.selectexigencia = MySelectExigenciaField;
    jsGrid.fields.selectsituacaorequisicao = MySelectSituacaoRequisicaoField;
    jsGrid.fields.datetime = MyDateTimeField;
    jsGrid.fields.date = MyDateField;
    jsGrid.fields.datetime = MyDateTimeField;
    jsGrid.fields.money = MoneyField;
    jsGrid.fields.doc = MyDocField;

    $("#" + name).jsGrid({
        width: '100%',
        filtering: true,
        sorting: true,
        paging: true,
        pageSize: 15,
        pageButtonCount: 5,
        autoload: true,
        pagerFormat: "{prev} {pages} {next}",
        pagePrevText: "Anterior",
        pageNextText: "Próximo",

        controller: {
            loadData: function (filter) {
                var d = $.Deferred();
                doPostAjaxCall(urlData, filter, function (response) {
                    d.resolve(response);
                });
                return d.promise();
            }
        },

        noDataContent: 'Nenhum Registro Encontrado',
        confirmDeleting: false,
        loadIndication: true,
        loadIndicationDelay: 1,
        loadMessage: "Aguarde...",
        loadShading: true,

        rowDoubleClick: function (args) {
            var link = linkVisualize;
            var a = '%5B%5B';
            var b = '%5D%5D';
            while (link.indexOf(a) >= 0) {
                var i = link.indexOf(a);
                var j = link.indexOf(b, i);
                var key = link.substring(i + a.length, j);
                link = link.replace(a + key + b, args.item[key]);
            }
            getView(link);
        },
        onRefreshed: function () {
            eventExecuteView();
        },

        fields: header
    });
}

function arredonda(numero, casasDecimais) {
    casasDecimais = typeof casasDecimais !== 'undefined' ? casasDecimais : 2;
    return +(Math.floor(numero + ('e+' + casasDecimais)) + ('e-' + casasDecimais));
}

function formataDinheiro(n, casasDecimais) {
    if (casasDecimais === undefined) {
        return n.toFixed(2).replace('.', ',').replace(/(\d)(?=(\d{3})+\,)/g, "$1.");
    } else {
        return n.toFixed(casasDecimais).replace('.', ',').replace(/(\d)(?=(\d{3})+\,)/g, "$1.");
    }

}


function formatReal(numero) {
    var tmp = numero + '';
    var neg = false;

    if (tmp - (Math.round(numero)) == 0) {
        tmp = tmp + '00';
    }

    if (tmp.indexOf(".")) {
        tmp = tmp.replace(".", "");
    }

    if (tmp.indexOf("-") == 0) {
        neg = true;
        tmp = tmp.replace("-", "");
    }

    if (tmp.length == 1) tmp = "0" + tmp

    tmp = tmp.replace(/([0-9]{2})$/g, ",$1");

    if (tmp.length > 6)
        tmp = tmp.replace(/([0-9]{3}),([0-9]{2}$)/g, ".$1,$2");

    if (tmp.length > 9)
        tmp = tmp.replace(/([0-9]{3}).([0-9]{3}),([0-9]{2}$)/g, ".$1.$2,$3");

    if (tmp.length = 12)
        tmp = tmp.replace(/([0-9]{3}).([0-9]{3}).([0-9]{3}),([0-9]{2}$)/g, ".$1.$2.$3,$4");

    if (tmp.length > 12)
        tmp = tmp.replace(/([0-9]{3}).([0-9]{3}).([0-9]{3}).([0-9]{3}),([0-9]{2}$)/g, ".$1.$2.$3.$4,$5");

    if (tmp.indexOf(".") == 0) tmp = tmp.replace(".", "");
    if (tmp.indexOf(",") == 0) tmp = tmp.replace(",", "0,");

    return (neg ? '-' + tmp : tmp);
}

function toggleSideMenu() {
    var vPageWrapper = $('#page-wrapper');
    $('#side-menu').toggle('slow');
    vPageWrapper.toggleClass('isOut');
    var isOut = vPageWrapper.hasClass('isOut');
    vPageWrapper.animate({marginLeft: isOut ? '0' : '250'}, 300);
}


//Foco com Enter

jQuery.extend(jQuery.expr[':'], {
    focusable: function (element) {
        return $(element).is('a, button, :input, .form-control, [tabindex]') && !$(element).is('.no-focus,[readonly],[disabled],[type="hidden"]');
    }
});

$('form.control-focus').on('keydown', ':focusable', function (e) {
    if (e.which == 13) {
        if (!$(this).is('textarea')) {
            e.preventDefault();
            var $canfocus = $(':focusable');
            var index = $canfocus.index(this) + 1;
            if (index >= $canfocus.length || $canfocus.eq(index)[0] == document.activeElement)
                index = 0;

            $canfocus.eq(index).focus();
        }
    }
});

function setSidrEvents() {

    $('#sidr_menu').sidr({
        body: '',
        onOpen: function (event) {
            $("#overlay").removeClass('d-none').addClass('d-flex');
        },
        onClose: function () {
            $('#overlay').removeClass('d-flex').addClass('d-none');
        }
    });

    $('#sidr-button').on('click', function () {
        $.sidr('open');

    });

    //ação para fechar o menu
    $('#closeAction').on('click', function () {
        $.sidr('close');

    });

    //ação para fechar o menu clicando no overlay
    $('#overlay').on('click', function (event) {
        $.sidr('close');

    });

    $.sidr('open');

}


/**
 * Função que adiciona os atalhos do sistema
 */
function setShortcuts() {


    $(document).keydown(function (evt) {



        /**Eventos para atalhos de menu
         * CTRL+Space: Abre o Menu Lateral
         * CTRL+Space ou ESC: Fecha o menu lateral
         */
        //81
        if (evt.keyCode == 32 && (evt.ctrlKey)) {
            evt.preventDefault();
            $.sidr('toggle');
        }

        if (evt.keyCode == 27) {
            evt.preventDefault();
            if ($.sidr('status').opened) {
                $.sidr('close');
            }
        }

        /**
         * Evento para acesso rápido à tela de módulos
         * CTRL+I: Retorna à tela de seleção de módulos
         */

        if (evt.keyCode == 73 && (evt.ctrlKey)) {
            evt.preventDefault();
            if ($.sidr('status').opened) {
                $.sidr('close');
            }
            mask();
            window.location.replace("/")
        }


    });

}

// Formata data
function formatDate(date) {
    if (date) {
        var _date = date.split('/');
        return (_date[2] + '-' + _date[1] + '-' + _date[0]);
    } else
        return '2100-01-01';
}


function disableF(selector) {
    $(selector)
        .on('focusin', function (event) {
            //$(this).datepicker('destroy');
            $(this).data('orig', $(this).val());
            //$(this).typeahead('destroy');
            //$(this).maskMoney('destroy');
        })
        .on('input click', function (event) {
            event.preventDefault();
            $(this).val($(this).data('orig'));
        }).addClass('only-view');
};

function hideF(selector) {
    $(selector).each(function () {
        $(this).hide();
    });
}


function disableFields() {
    //Remove-se todos os eventos de TODOS os campos com a classe "disableVisualize"
    $('.disableVisualize').each(function () {
        $(this).off();
        if ($(this).prop('type') == 'radio') {
            $(this).attr('disabled', true);
        }
    });


    disableF('.disableVisualize');
    hideF('.hideVisualize');

    $('.w2ui-grid').each(function () {
        var grid = w2ui[$(this).attr('id')];
        for (var i = 0; i < grid.columns.length; i++) {
            grid.columns[i].editable = false;
        }
    });

    //Detroy TODOS os Typeaheads em modo de Visualização
    /*$('input[data-key]').each(function(){
        $(this).typeahead("destroy");
        //$(this).unbind();
    });*/

    // $('.disableVisualize').attr('readonly', true).css('background-color','#EEE');
    $('#form1').attr('action', '');
    $('#form1').attr('method', '');
};

function disableForm() {
    disableFields();
}

function str(str){
    if (!str){
        return "";
    }
}

