<?php

function ImportCSS()
{
    echo '
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
            rel="stylesheet"
        >

        <link
            rel="stylesheet"
            href="/WebCS_G6_Proyecto/View/css/style.css"
        >
    ';
}

function ImportJS()
{
    echo '
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        <script src="/WebCS_G6_Proyecto/View/js/main.js"></script>
    ';
}