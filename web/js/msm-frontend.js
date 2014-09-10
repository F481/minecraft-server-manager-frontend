function MSM_Frontend() {
    this.basePath = window.location.pathname;

    this.init = function () {
        // hide elements
        $(".alert, #overlay, #loader").hide();

        $(document).bind("ajaxSend",function () {
            showOverlay(true);
            $("#loader").show();
        }).bind("ajaxComplete", function () {
            $("#loader").hide();
        });
    }

    this.createServer = function () {
        var serverName = prompt("Enter the name of the new server");

        if (serverName != null) {
            $.get(this.basePath + "/msm/create", { name: serverName },function () {
                openAlert("success", "Server successfully created! :)");
            }).fail(function () {
                openAlert("danger", "Something went wrong :(");
            });
        }
    }

    var showOverlay = function (show) {
        show ? $("#overlay").show() : $("#overlay").hide();
    }

    var openAlert = function (type, text) {
        showOverlay(true);

        switch (type) {
            case "success":
                $(".alert-success p").text(text);
                $(".alert-success").show();
                break;
            case "danger":
                $(".alert-danger p").text(text);
                $(".alert-danger").show();
        }

        $(".alert .close:button").click(function () {
            $(".alert").hide();
            showOverlay(false);
        });
    };
}