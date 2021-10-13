
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

                    error_msg += '<span style="color: indianred;"><b>*</b></span>' + error[key] + '<br>';

                }
                msg(error_msg, false, 'error');
                return;
            }

            if (json.status == 500) {

                error_msg = '<b>A operação não pôde ser realizada porque: </b><br><br>' + json.responseText;
                msg(error_msg, false, 'log');

                return;
            }

            // $('body').html(json.responseText);
        }, 'html');
}

function executeView(view) {
    $('#contentView').html(view);

    eventExecuteView();
    setRequiredFields();
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

    if (!div) {
        div = 'mask';
    }

    $("#" + div).removeClass('d-flex').addClass('d-none');
    return;
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

