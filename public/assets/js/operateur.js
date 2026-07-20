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

function initOptionFraisRetrait(idPrefixe, operateurExpediteur, idBloc, idCheckbox) {
    var champPrefixe = document.getElementById(idPrefixe);
    var bloc = document.getElementById(idBloc);
    var checkbox = document.getElementById(idCheckbox);

    if (!champPrefixe || !bloc || !checkbox) {
        return;
    }

    function mettreAJour() {
        var option = champPrefixe.options[champPrefixe.selectedIndex];
        var organisation = option ? option.getAttribute('data-organisation') : '';

        if (organisation === operateurExpediteur) {
            bloc.style.display = '';
        } else {
            bloc.style.display = 'none';
            checkbox.checked = false;
        }
    }

    champPrefixe.addEventListener('change', mettreAJour);
    mettreAJour();
}

function initTransfertMultiple(idListe, idModele, idBoutonAjouter, idMontantTotal, idApercu) {
    var liste = document.getElementById(idListe);
    var modele = document.getElementById(idModele);
    var boutonAjouter = document.getElementById(idBoutonAjouter);
    var champMontantTotal = document.getElementById(idMontantTotal);
    var blocApercu = document.getElementById(idApercu);

    if (!liste || !modele || !boutonAjouter || !champMontantTotal || !blocApercu) {
        return;
    }

    function compterDestinataires() {
        return liste.querySelectorAll('.ligne-destinataire-multiple').length;
    }

    function mettreAJourApercu() {
        var montantTotal = parseFloat(champMontantTotal.value) || 0;
        var nombre = compterDestinataires();
        var montantParDestinataire = nombre > 0 ? montantTotal / nombre : 0;

        blocApercu.innerHTML = 'Montant par destinataire : ' + montantParDestinataire.toLocaleString('fr-FR') + ' Ar';
    }

    function attacherSuppression(ligne) {
        var bouton = ligne.querySelector('.btn-supprimer-destinataire-multiple');
        if (bouton) {
            bouton.addEventListener('click', function() {
                if (compterDestinataires() > 1) {
                    ligne.remove();
                    mettreAJourApercu();
                }
            });
        }
    }

    liste.querySelectorAll('.ligne-destinataire-multiple').forEach(attacherSuppression);

    boutonAjouter.addEventListener('click', function() {
        var fragment = modele.content.cloneNode(true);
        liste.appendChild(fragment);
        attacherSuppression(liste.lastElementChild);
        mettreAJourApercu();
    });

    champMontantTotal.addEventListener('input', mettreAJourApercu);
    mettreAJourApercu();
}