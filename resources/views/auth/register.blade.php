<!doctype html>
<html lang="en"> 

<head> 
  <meta charset="UTF-8"> 
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Russell Osias Social Media - Register</title> 
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap');
    *
    {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Quicksand', sans-serif;
    }
    body 
    {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      background: #000;
    }
    section 
    {
      position: absolute;
      width: 100vw;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 2px;
      flex-wrap: wrap;
      overflow: hidden;
    }
    section::before 
    {
      content: '';
      position: absolute;
      width: 100%;
      height: 100%;
      background: linear-gradient(#000, #f00, #000);
      animation: animate 5s linear infinite;
    }
    @keyframes animate 
    {
      0% {
        transform: translateY(-100%);
      }
      100% {
        transform: translateY(100%);
      }
    }
    section span 
    {
      position: relative;
      display: block;
      width: calc(6.25vw - 2px);
      height: calc(6.25vw - 2px);
      background: #181818;
      z-index: 2;
      transition: 1.5s;
    }
    section span:hover 
    {
      background: #f00;
      transition: 0s;
    }
    section .register
    {
      position: absolute;
      width: 400px;
      background: #222;  
      z-index: 1000;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 40px;
      border-radius: 4px;
      box-shadow: 0 15px 35px rgba(0,0,0,9);
    }
    section .register .content 
    {
      position: relative;
      width: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      gap: 20px;
    }
    section .register .content h2 
    {
      font-size: 2em;
      color: #f00;
      text-transform: uppercase;
    }
    section .register .content .form 
    {
      width: 100%;
      display: flex;
      flex-direction: column;
      gap: 25px;
    }
    section .register .content .form .inputBox
    {
      position: relative;
      width: 100%;
    }
    section .register .content .form .inputBox input 
    {
      position: relative;
      width: 100%;
      background: #333;
      border: none;
      outline: none;
      padding: 25px 10px 7.5px;
      border-radius: 4px;
      color: #fff;
      font-weight: 500;
      font-size: 1em;
    }
    section .register .content .form .inputBox i 
    {
      position: absolute;
      left: 0;
      padding: 15px 10px;
      font-style: normal;
      color: #aaa;
      transition: 0.5s;
      pointer-events: none;
    }
    .register .content .form .inputBox input:focus ~ i,
    .register .content .form .inputBox input:valid ~ i
    {
      transform: translateY(-7.5px);
      font-size: 0.8em;
      color: #fff;
    }
    .register .content .form .links 
    {
      position: relative;
      width: 100%;
      display: flex;
      justify-content: space-between;
    }
    .register .content .form .links a 
    {
      color: #fff;
      text-decoration: none;
    }
    .register .content .form .links a:nth-child(2)
    {
      color: #f00;
      font-weight: 600;
    }
    .register .content .form .inputBox input[type="submit"]
    {
      padding: 10px;
      background: #f00;
      color: #000;
      font-weight: 600;
      font-size: 1.35em;
      letter-spacing: 0.05em;
      cursor: pointer;
    }
    input[type="submit"]:active
    {
      opacity: 0.6;
    }
  </style>
</head> 

<body>

 <section> 
   <!-- Background Animation -->
 <section> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> 
  <!-- Register Form Design -->
   <div class="register">
     <div class="content">
       <h2>Register</h2>
       <div class="form">
         <form method="POST" action="{{ route('register') }}">
           @csrf

           <!-- Name Field -->
           <div class="inputBox">
             <label for="name">👤 Full Name</label>
             <input type="text" id="name" name="name" required autofocus autocomplete="name" placeholder=" ">
           </div>

           <!-- Email Field -->
           <div class="inputBox">
             <label for="email">📧 Email Address</label>
             <input type="email" id="email" name="email" required autocomplete="username" placeholder=" ">
           </div>

           <!-- Password Field -->
           <div class="inputBox">
             <label for="password">🔒 Password</label>
             <input type="password" id="password" name="password" required autocomplete="new-password" placeholder=" ">
           </div>

           <!-- Confirm Password Field -->
           <div class="inputBox">
             <label for="password_confirmation">🔒 Confirm Password</label>
             <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password" placeholder=" ">
           </div>

           <!-- Submit Button -->
           <div class="inputBox">
             <input type="submit" value="Register">
           </div>

           <!-- Links (Already registered?) -->
           <div class="links">
             <a href="{{ route('login') }}">Already registered?</a>
           </div>

         </form>
       </div>
     </div>
   </div>
 </section>

</body>

</html>
