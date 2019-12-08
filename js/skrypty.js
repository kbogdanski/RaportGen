/**
 * Created by Kamil on 2019-12-08.
 */

$(function() {

    $('#button_stale_przepl_pienieznych').on('click', function () {
        $('#stale_przeplywow_pienieznych').toggleClass('hidden');
    });

    $('#button_wartosci_korekty').on('click', function () {
        $('#wartosci_korekty').toggleClass('hidden');
    });

    $('#button_stopy_wzrostu').on('click', function () {
        $('#stopy_wzrostu').toggleClass('hidden');
    });

});