function initApercuFrais(idMontant, idTranches, idApercu) {
    var champMontant = document.getElementById(idMontant);
    var blocTranches = document.getElementById(idTranches);
    var blocApercu = document.getElementById(idApercu);

    if (!champMontant || !blocTranches || !blocApercu) {
        return;
    }

    var tranches = JSON.parse(blocTranches.textContent || '[]');

    function calculerFrais(montant) {
        for (var i = 0; i < tranches.length; i++) {
            var tranche = tranches[i];
            if (montant >= parseFloat(tranche.montant_min) && montant <= parseFloat(tranche.montant_max)) {
                return parseFloat(tranche.frais);
            }
        }
        return 0;
    }

    function mettreAJour() {
        var montant = parseFloat(champMontant.value) || 0;
        var frais = calculerFrais(montant);
        var total = montant + frais;

        blocApercu.innerHTML = 'Frais estime : ' + frais.toLocaleString('fr-FR') + ' Ar<br>' +
            'Total debite : ' + total.toLocaleString('fr-FR') + ' Ar';
    }

    champMontant.addEventListener('input', mettreAJour);
    mettreAJour();
}