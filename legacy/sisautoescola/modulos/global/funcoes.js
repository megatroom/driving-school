
var caminho_modulos = "modulos/";

var openTabs = new Array();
var openTabsCount = 0;

var vWindowType = "1";

function setWidowType(pWindowType) {
    vWindowType = pWindowType;
}

function formatarHoraSeparada(pHora, pMinuto) {
    var vHora = pHora;
    var vMinuto = pMinuto;
    if (vHora < 10) {
        vHora = "0" + vHora;
    }
    if (vMinuto < 10) {
        vMinuto = "0" + vMinuto;
    }
    return vHora + ":" + vMinuto;
}

function openAjax(pURL) {
    if (vWindowType == "1") {
        var $tabs = $('#tabs').tabs();
        var selected = $tabs.tabs('option', 'selected');
        $tabs.tabs('url', selected, pURL);
        $tabs.tabs('load', selected);
        event.preventDefault();
    } else {
        $.post(pURL, {}, function(data) {
            $("#windowContent").html(data);
        });
    }
}

function openRelatorio(pURL) {
    window.open(
        pURL,
        "_blank",
        "fullscreen=yes,scrollbars=yes,location=no,menubar=no,titlebar=yes,toolbar=no,left=0px,top=0px");
}

// Funcao para abrir nova aba no menu principal
function novaAbaMenuPrincipal(pId, pClasse, pNome) {
    vClasse = pClasse + "?pCloseId=" + pId;
    if (vWindowType == "1") {
        if (pId > 0) {
            if (openTabs != null) {
                for (i=0;i<openTabs.length;i++) {
                    if (openTabs[i] == pId) {
                        return false;
                    }
                }
            }
            openTabs[openTabsCount] = pId;
            openTabsCount++;
            $("#tabs").tabs('add', vClasse, pNome);
            $("#tabs").tabs('select', $("#tabs").tabs('length')-1);
        }            
    } else {
        $("#windowTitle").html(pNome);
        $.post(vClasse, {}, function(data) {
            $("#windowContent").html(data);
        });
    }

    return true;
}
function novaAbaMenuPrincipalComParametro(pClasse, pParametros, pNome) {
    vClasse = pClasse;
    $("#windowTitle").html(pNome);
    $.post(vClasse, pParametros, function(data) {
        $("#windowContent").html(data);
    });
}
function fecharAbaMenuPrincipal(pId) {
    if (vWindowType == "1") {
        for (i=0;i<openTabs.length;i++) {
            if (openTabs[i] == pId) {
                openTabs[i] = 0;
            }
        }
        var $tabs = $('#tabs').tabs();
        var selected = $tabs.tabs('option', 'selected');
        $tabs.tabs('remove', selected);
    } else {
        $("#windowTitle").html("Bem Vindo");
        openAjax("modulos/index.php");
    }
}

function dialogModalMsg(pDivNameTitle, pDivNameMsg, pTitle, pMsg) {
    $(document).ready(function(){        
        $("#"+pDivNameMsg).html(pMsg);
        $("#"+pDivNameTitle).dialog('option', 'title', pTitle);
        $("#"+pDivNameTitle).dialog('open');
    });
}

function divCloseAlert(pDivName) {
    txtMsg = '<div style="float:right;margin-left:10px;"><a id="dvCloseAlert" href="#"><span class="ui-icon ui-icon-circle-close" /></a></div>';

    txtMsg = txtMsg + '<script type="text/javascript"> ';
    txtMsg = txtMsg + '$(document).ready(function(){ ';
    txtMsg = txtMsg + '$("#dvCloseAlert").click(function(event){ ';
    txtMsg = txtMsg + '$("#'+pDivName+'").hide("fast"); ';
    txtMsg = txtMsg + 'event.preventDefault(); ';
    txtMsg = txtMsg + '}); ';
    txtMsg = txtMsg + '}); ';
    txtMsg = txtMsg + '</script> ';

    return txtMsg;
}

function postSucess(pDivName, pType) {
    var txtMsg = "";

    if (pType == "save") {
        txtMsg = $icon_alert + '<b>Cadastro efetuado com sucesso.</b>';
    } else if (pType == "delete") {
        txtMsg = $icon_alert + '<b>Registro excluído com sucesso.</b>';
    }

    txtMsg = txtMsg + divCloseAlert(pDivName);

    $("#"+pDivName).html(txtMsg);
    $("#"+pDivName).show("fast");

    return true;
}


function divAlertCustomBasic(pDivName, pTitulo) {
    var txtMsg = "";

    txtMsg = $icon_alert + '<b>'+ pTitulo +'</b>';

    txtMsg = txtMsg + divCloseAlert(pDivName);

    $("#"+pDivName).html(txtMsg);
    $("#"+pDivName).show("fast");

    return true;
}

function divAlertCustomSplitMsg(pDivName, pTitulo, pMsg) {
    var txtMsg = "";
    var i;

    txtMsg = $icon_alert + '<b>'+ pTitulo +'</b>';

    txtMsg = txtMsg + divCloseAlert(pDivName);

    txtMsg = txtMsg + "<ul>";

    vMsg = pMsg.split("|");
    for (i in vMsg) {
        txtMsg = txtMsg + "<li>" + vMsg[i] + "</li>";
    }

    txtMsg = txtMsg + "</ul>";

    $("#"+pDivName).html(txtMsg);
    $("#"+pDivName).show("fast");

    return true;
}

function postAlert(pRetornoStatus, pTitulo, pMsg, pReturn, pDivAlertName) {
    var txtMsg = "";
    var i;

    txtMsg = $icon_alert + "<b>" + pTitulo + "</b>";

    txtMsg = txtMsg + divCloseAlert(pDivAlertName);

    if (pRetornoStatus == "validacao") {
        txtMsg = txtMsg + "<ul>";
        for (i in pMsg) {
            txtMsg = txtMsg + "<li>" + pMsg[i] + "</li>";
        }
        txtMsg = txtMsg + "</ul>";
    } else if (pRetornoStatus == "erro") {
        txtMsg = txtMsg + "<ul>";
        for (i in pMsg) {
            txtMsg = txtMsg + "<li>" + pMsg[i] + "</li>";
        }
        txtMsg = txtMsg + "</ul>";
    } else if (pRetornoStatus == "save") {
        if (pReturn.indexOf('?') >= 0) {
            openAjax(pReturn + '&type_msg=save');
        } else {
            openAjax(pReturn + '?type_msg=save');
        }
    } else if (pRetornoStatus == "delete") {
        if (pReturn.indexOf('?') >= 0) {
            openAjax(pReturn + '&type_msg=delete');
        } else {
            openAjax(pReturn + '?type_msg=delete');
        }
    } else {
        return false;
    }

    $("#"+pDivAlertName).html(txtMsg);
    $("#"+pDivAlertName).show("fast");

    return true;
}

function toggleMenuTopo() {
    var windowName = "";
    if (vWindowType == 1) {
        windowName = "#tabs";
    } else {
        windowName = "#windowBox";
    }
    if($("#indexTopo").css("display") == "block") {
        $("#indexMenu").hide("slow");
        $("#indexTopo").hide("slow");
        $(windowName).css("left", "20px");
    } else {
        $("#indexMenu").show("slow");
        $("#indexTopo").show("slow");
        $(windowName).css("left", "238px");
    }
}