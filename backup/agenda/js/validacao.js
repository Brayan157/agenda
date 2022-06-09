$(document).ready(function{
    var hoje = new Date();
    var nasc;
    var idade;
    var m;
   $('.date').on('bluer', function() {
        nasc = new Date($('.date').value);
        var idade = hoje.getFullYear() - nasc.getFullYear();
        var m = hoje.getMonth() - nasc.getMonth();
        if (m < 0 || (m === 0 && hoje.getDate() < nasc.getDate())) idade--;

        if(idade < 18){
            alert("Pessoas menores de 18 não podem se cadastrar.");
            return false;
        }
        if(idade >= 18){
            alert("Maior de 18, pode se cadastrar.");
        }
        // se for maior que 60 não vai acontecer nada!
        
   }); 
});