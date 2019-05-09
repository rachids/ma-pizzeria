$(document).ready(function(){
    $("#nombre").on("change", function(){
        var cost;
        var base;
        var amount;

        base = '.$restaurant->etatMax.'
        amount = parseInt($("#nombre").val());
        reach = base + amount

        cost = (((reach-50)-(base-50)+1)*((reach-50)+(base-50))*'.$this->config->item('restaurant_upgradePDB').')/2;

        $("#costAmeliorateHealth").html(cost);
    });

    $("#nombreReparation").on("change", function(){
        var cost;
        var amount;

        amount = parseInt($("#nombreReparation").val());

        cost = (amount+1)*(amount*'.$this->config->item('restaurant_reparer').')/2
        $("#costRepairHealth").html(cost);
    });
});