$(document).ready(function(){
    let $form = document.querySelector("#filter-form");
    let $resultContainer = document.querySelector(".container-result");

    let lastAjaxParams = null;
    let pID = null;

    function getSubmitParams() {
        let result = {};
        let $items = $form.querySelectorAll("input,select");


        $items.forEach(function($item){
            let name = $item.getAttribute('name');
            let value = $item.value;

            if( name == null || value == null || value == "" ) {
                return;
            }

            result[name] = value;
        });
        return result;
    }

    function resetForm(params){
        if(params !== null) {
            params == null
        }

        $.get("", params, function(data) {
            data = data.replace(/\t/g, "");
            data = data.replace(/\n/g, "");
            data = data.match(/<div class="container-result">(.*)<\/div>/);
            $resultContainer.innerHTML = data[1];
            pagination();
        });
    }

    function pagination() {
        function getPaginationData(href) {
            let params = href.match(/\?(.*)/);

            if( params == null ) {
                params = "PAGEN_1=1";
            }
            else {
                params = params[1];
            }

            params = params.split("&");
            let paginationID = null;
            let pageID = null;

            params.forEach(function(param){
                param = param.split("=");
                let len = "PAGEN_".length;

                if( param[0].substr(0, len) != "PAGEN_" ) {
                    return;
                }

                paginationID = param[0].substr(len);
                pageID = param[1];
            });

            return {ID: paginationID, page: pageID};
        }

        let $items = $resultContainer.querySelectorAll("ul.pagination li a");

        $items.forEach(function($item){
            let href = $item.getAttribute("href");

            if( href == false ) {
                return;
            }

            let {ID:paginationID, page:pageID} = getPaginationData(href);

            if( pID == null ) {
                pID = paginationID;
            }

            $item.addEventListener('click', function(event){
                submitForm(null, pageID);
                event.preventDefault();
            });
        });
    }

    function submitForm(params = null, page = 1) {

        if( params == null ) {
            if( lastAjaxParams == null ) {
                lastAjaxParams = getSubmitParams();
            }

            params = lastAjaxParams;
        }
        
        params['PAGEN_' + pID] = page;

        $.get("", params, function(data) {
            data = data.replace(/\t/g, "");
            data = data.replace(/\n/g, "");
            data = data.match(/<div class="container-result">(.*)<\/div>/);
            $resultContainer.innerHTML = data[1];
            pagination();
        });
    }

    $form.addEventListener("submit", function(event){
        submitForm(getSubmitParams(), 1);
        event.preventDefault();
    });

    let $items = $form.querySelectorAll("input,select");
    $items.forEach(function($item) {
        $item.addEventListener("change", function(){
            submitForm(getSubmitParams(), 1);
        })
    })

    let resetBtn = $form.querySelector('input[type="reset"]');
    resetBtn.addEventListener('click', function () {
        submitForm(resetForm(lastAjaxParams))
    })

    pagination();
});