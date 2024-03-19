
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="icon" href="/favicon.ico" type="image/x-icon" />
    <title>{{ $document->title }}</title>

    <style>
        html {
            height: 100%;
            width: 100%;
        }

        body {
            background: #fff;
            color: #333;
            font-family: Arial, Tahoma,sans-serif;
            font-weight: normal;
            height: 100%;
            margin: 0;
            overflow-y: hidden;
            padding: 0;
            text-decoration: none;
        }

        form {
            height: 100%;
        }

        div {
            margin: 0;
            padding: 0;
        }
    </style>

    <script type="text/javascript" src="{{ config('docserver.doc_serv_api_url') }}"></script>

    <script type="text/javascript">

        var docEditor;
        var fileName = "{{ $id }}".replace(/&amp;/g, "&");
        var fileType = "{{ ltrim(getInternalExtension($document->filename), '.') }}";
        var onAppReady = function () {
            console.log("Document editor ready");
        };

        var onDocumentStateChange = function (event) {
            var title = document.title.replace(/\*$/g, "");
            document.title = title + (event.data ? "*" : "");
        };

        var onRequestEditRights = function () {
            location.href = location.href.replace(RegExp("action=view\&?", "i"), "");
        };

        var onError = function (event) {
            if (event)
                console.log(event.data);
        };

        var onOutdatedVersion = function (event) {
            location.reload(true);
        };

        var сonnectEditor = function () {
            var user = {!! json_encode($author) !!}
            var type = "{{ $type }}";

            if (type == "") {
                type = new RegExp("android|avantgo|playbook|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od|ad)|iris|kindle|lge |maemo|midp|mmp|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino", "i").test(window.navigator.userAgent) ? "mobile" : "desktop";
            }

            docEditor = new DocsAPI.DocEditor("iframeEditor", {
                width: "100%",
                height: "100%",
                type: type,
                documentType: "{{ getDocumentType($document->filename) }}",
                document: {
                    title: "{{ $document->title }}".replace(/&amp;/g, "&"),
                    url: "{{ $document->url }}".replace(/&amp;/g, "&"),
                    fileType: fileType,
                    key: "{{ $document->key }}",
                    info: {
                        owner: "{{ $author->name }}",
                        created: "{{ $document->createdAt }}",
                    },

                    permissions: {!! json_encode($document->permission) !!}
                },
                editorConfig: {
                    mode: '{{ $mode }}',
                    lang: "en",
                    callbackUrl: "{{ route('docserver.callback') }}?type=track&fileName={{ $path ?? $id }}&userAddress={{ request()->getClientIp() }}",

                    user: user,

                    embedded: {
                        saveUrl: "{{ $document->url }}?save",
                        embedUrl: "{{ $document->url }}?embed",
                        shareUrl: "{{ $document->url }}?share",
                        toolbarDocked: "top",
                    },

                    customization: {
                        about: false,
                        feedback: false,
                        hideRightMenu: true,
                        reviewDisplay: "markup",
                        customer: {
                            name: "BPK RI",
                            info: "Badan Pemeriksa Keuangan Republik Indonesia",
                            address: "Jl Gatot Subroto Kav. 31"
                        },
                        @if (!empty($document->logo))
                        logo: {!! json_encode($document->logo) !!},
                        @endif

                        compactToolbar: false
                        @if (!empty($document->returnUrl))
                        , goback: {
                            url: "{{ $document->returnUrl }}",
                        },
                        @endif
                    }

                    @if (!empty($document->plugins))
                    , plugins: {!! json_encode($document->plugins) !!}
                    @endif

                },
                events: {
                    'onAppReady': onAppReady,
                    'onDocumentStateChange': onDocumentStateChange,
                    'onRequestEditRights': onRequestEditRights,
                    'onError': onError,
                    'onOutdatedVersion': onOutdatedVersion,
                }
            });
        };

        if (window.addEventListener) {
            window.addEventListener("load", сonnectEditor);
        } else if (window.attachEvent) {
            window.attachEvent("load", сonnectEditor);
        }

    </script>
</head>
<body>
    <form id="form1">
        <div id="iframeEditor">
        </div>
    </form>
</body>
</html>