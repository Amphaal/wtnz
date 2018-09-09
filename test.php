<html>
    <head>
        <style>
        
        @keyframes Gradient {
            0% {
                background-position: 0% 50%
            }
            50% {
                background-position: 100% 50%
            }
            100% {
                background-position: 0% 50%
            }
        }

        .anim {
            background: linear-gradient(-45deg, #EE7752, #E73C7E, #23A6D5, #23D5AB);
            background-size: 400% 400%;

            animation: Gradient 15s ease infinite;
        }



        </style>
    </head>
    <body class='anim'>

    </body>
</html>