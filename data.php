<?php

$effects = new EffectCollection();

$effects::addEffect(new Effect('Blindheit', false));
$effects::addEffect(new Effect('Energieresistenz erhöhen', true));
$effects::addEffect(new Effect('Energieresistenz senken', false));
//$effects::addEffect(new Effect('Energieschaden', false)); // wahrscheinlich weg
$effects::addEffect(new Effect('Enthüllung', false));
$effects::addEffect(new Effect('Erfrischung', true));
$effects::addEffect(new Effect('Feder', true));
$effects::addEffect(new Effect('Feuerresistenz erhöhen', true));
$effects::addEffect(new Effect('Feuerresistenz senken', false));
$effects::addEffect(new Effect('Feuerschaden', false));
$effects::addEffect(new Effect('Gegengift', true));
$effects::addEffect(new Effect('Geschick', true));
$effects::addEffect(new Effect('Giftresistenz erhöhen', true));
$effects::addEffect(new Effect('Giftresistenz senken', false));
$effects::addEffect(new Effect('Giftschaden', false));
$effects::addEffect(new Effect('Haarausfall', false));
$effects::addEffect(new Effect('Haarwuchs', true));
$effects::addEffect(new Effect('Halluzinationen', false)); //
$effects::addEffect(new Effect('Heilung', true));
$effects::addEffect(new Effect('Hunger', false));
$effects::addEffect(new Effect('Infizieren', false));
$effects::addEffect(new Effect('Intelligenz', true));
$effects::addEffect(new Effect('Kälteschaden', false)); //
$effects::addEffect(new Effect('Krankheitsresistenz erhöhen', true));
$effects::addEffect(new Effect('Last', false));
$effects::addEffect(new Effect('Lebensenergieentzug', false));
$effects::addEffect(new Effect('leichte Krankheit heilen', true));
$effects::addEffect(new Effect('Magieresistenz erhöhen', true));
$effects::addEffect(new Effect('Magieresistenz senken', false));
$effects::addEffect(new Effect('Manawiederherstellung', true));
$effects::addEffect(new Effect('Manaentzug', false));
$effects::addEffect(new Effect('Nachtsicht', true));
$effects::addEffect(new Effect('Paralyse', false));
$effects::addEffect(new Effect('Paralyse aufheben', true));
$effects::addEffect(new Effect('Physische Resistenz erhöhen', true));
$effects::addEffect(new Effect('Physische Resistenz senken', false));
$effects::addEffect(new Effect('Sättigung', true));
$effects::addEffect(new Effect('Säureschaden', false)); //
$effects::addEffect(new Effect('Schlaf', false));
$effects::addEffect(new Effect('Schminke', true));
$effects::addEffect(new Effect('Schutz', true));
$effects::addEffect(new Effect('Schwäche', false));
$effects::addEffect(new Effect('Schwachsinn', false));
$effects::addEffect(new Effect('schwere Krankheit heilen', true));
$effects::addEffect(new Effect('Stärke', true));
$effects::addEffect(new Effect('Tollpatschigkeit', false));
$effects::addEffect(new Effect('Unsichtbarkeit', true));
$effects::addEffect(new Effect('Zauber auflösen', true));

Regency::setEffectCollection($effects);

$regencies = new RegencyCollection();
$regencies::addRegency(new Regency('Alraune', ['Stärke', 'Intelligenz', 'Giftresistenz erhöhen', 'Blindheit']));
$regencies::addRegency(new Regency('Apfel', ['Stärke', 'Heilung', 'Haarwuchs', 'Last']));
$regencies::addRegency(new Regency('Bims', ['Feder', 'Nachtsicht', 'Feuerresistenz erhöhen', 'Energieresistenz erhöhen']));
$regencies::addRegency(new Regency('Blut', ['Stärke', 'Intelligenz', 'Tollpatschigkeit']));
$regencies::addRegency(new Regency('Blutmoos', ['Geschick', 'Krankheitsresistenz erhöhen', 'Schminke', 'Schwachsinn']));
$regencies::addRegency(new Regency('Champignon', ['Intelligenz', 'Sättigung', 'Haarwuchs', 'Hunger']));
$regencies::addRegency(new Regency('Coeliumerz', ['Nachtsicht', 'Energieresistenz erhöhen', 'Enthüllung' /*, 'Energieschaden'*/]));
$regencies::addRegency(new Regency('Dämonenknochen', ['Physische Resistenz erhöhen', 'Manawiederherstellung', 'Magieresistenz senken', 'Physische Resistenz senken']));
$regencies::addRegency(new Regency('Diamanterz', ['Physische Resistenz erhöhen', 'Unsichtbarkeit', 'Schutz', 'Lebensenergieentzug']));
$regencies::addRegency(new Regency('Drachenblut', ['Magieresistenz erhöhen', 'Zauber auflösen', 'Manawiederherstellung', 'Schlaf']));
$regencies::addRegency(new Regency('Efeu', ['Heilung', 'Unsichtbarkeit', 'Schlaf', 'Giftschaden']));
$regencies::addRegency(new Regency('Eisenerz', ['Stärke', 'Schutz', 'Tollpatschigkeit', 'Schwachsinn']));
$regencies::addRegency(new Regency('Fingerhut', ['leichte Krankheit heilen', 'Paralyse aufheben', 'Energieresistenz senken']));
$regencies::addRegency(new Regency('Fledermausflügel', ['Feder', 'Krankheitsresistenz erhöhen', 'Manaentzug', 'Blindheit']));
$regencies::addRegency(new Regency('Fliegenpilz', ['Manawiederherstellung', 'Manaentzug', 'Giftschaden', 'Lebensenergieentzug']));
$regencies::addRegency(new Regency('Ginsengwurzel', ['Heilung', 'leichte Krankheit heilen', 'Erfrischung', 'Gegengift']));
$regencies::addRegency(new Regency('Grabmoos', ['Heilung', 'Schutz', 'Halluzinationen', 'Schminke']));
$regencies::addRegency(new Regency('grüne Traube', ['Stärke', 'Heilung', 'Haarwuchs', 'Paralyse']));
$regencies::addRegency(new Regency('Henkerskappe', ['Zauber auflösen', 'Manawiederherstellung', 'Paralyse', 'Blindheit']));
$regencies::addRegency(new Regency('Kaktus', ['Feder', 'Krankheitsresistenz erhöhen', 'Sättigung', 'Haarausfall']));
$regencies::addRegency(new Regency('Knoblauch', ['Giftresistenz erhöhen', 'Krankheitsresistenz erhöhen', 'Gegengift', 'schwere Krankheit heilen']));
$regencies::addRegency(new Regency('Knochen', ['Magieresistenz erhöhen', 'Schwäche', 'Kälteschaden']));
$regencies::addRegency(new Regency('Krötenlaich', ['Paralyse aufheben', 'Tollpatschigkeit', 'Infizieren', 'Säureschaden']));
$regencies::addRegency(new Regency('Kupfererz', ['Geschick', 'Schutz', 'Schwäche', 'Schwachsinn']));
$regencies::addRegency(new Regency('Lehm', ['Physische Resistenz erhöhen', 'Sättigung', 'Blindheit', 'Säureschaden']));
$regencies::addRegency(new Regency('Limone', ['leichte Krankheit heilen', 'Gegengift', 'Säureschaden', 'Feuerresistenz senken']));
$regencies::addRegency(new Regency('Molchauge', ['Schwäche', 'Haarausfall', 'Halluzinationen', 'Physische Resistenz senken']));
$regencies::addRegency(new Regency('Nachtschatten', ['Nachtsicht', 'schwere Krankheit heilen', 'Giftschaden', 'Lebensenergieentzug']));
$regencies::addRegency(new Regency('Obsidian', ['Unsichtbarkeit', 'leichte Krankheit heilen', 'Giftschaden']));
$regencies::addRegency(new Regency('Pfirsich', ['Geschick', 'leichte Krankheit heilen', 'Paralyse aufheben', 'Erfrischung']));
$regencies::addRegency(new Regency('Pyrianerz', ['Feuerresistenz erhöhen', 'Last', 'Enthüllung', 'Feuerschaden']));
$regencies::addRegency(new Regency('Rattenfleisch', ['Schwachsinn', 'Schlaf', 'Infizieren', 'Giftresistenz senken']));
$regencies::addRegency(new Regency('roher Vogel', ['Energieresistenz erhöhen', 'Sättigung', 'Haarwuchs', 'Infizieren']));
$regencies::addRegency(new Regency('Schlangenschuppe', ['Nachtsicht', 'Hunger', 'Haarausfall', 'Infizieren']));
$regencies::addRegency(new Regency('schwarze Perle', ['Stärke', 'Zauber auflösen', 'Erfrischung', 'Blindheit']));
$regencies::addRegency(new Regency('Schwefel', ['Intelligenz', 'Schlaf', 'Manaentzug', 'Lebensenergieentzug']));
$regencies::addRegency(new Regency('Schwefelasche', ['Geschick', 'Giftschaden', 'Feuerschaden', 'Feuerresistenz senken']));
$regencies::addRegency(new Regency('Seerose', ['schwere Krankheit heilen', 'Schwachsinn', 'Schlaf', 'Haarausfall']));
$regencies::addRegency(new Regency('Spinnenseide', ['Feder', 'Nachtsicht', 'Giftresistenz erhöhen', 'Paralyse']));
$regencies::addRegency(new Regency('Torf', ['Zauber auflösen', 'Last', 'Giftresistenz senken']));
$regencies::addRegency(new Regency('totes Holz', ['Stärke', 'leichte Krankheit heilen', 'Kälteschaden', 'Halluzinationen']));
$regencies::addRegency(new Regency('Traube', ['Stärke', 'Heilung', 'Feder', 'Hunger']));
$regencies::addRegency(new Regency('Vulkanasche', ['Feuerresistenz erhöhen', 'Schwäche', 'Magieresistenz senken', 'Säureschaden']));
$regencies::addRegency(new Regency('Weicheisen', ['Feuerresistenz erhöhen', 'Sättigung', 'Last', 'Energieresistenz senken']));
$regencies::addRegency(new Regency('Wyrmherz', ['Heilung', 'Magieresistenz erhöhen', 'Unsichtbarkeit', 'Schutz']));
$regencies::addRegency(new Regency('Zitrone', ['leichte Krankheit heilen', 'Gegengift', 'Feuerresistenz senken']));
$regencies::addRegency(new Regency('Zwiebel', ['Krankheitsresistenz erhöhen', 'Paralyse aufheben', 'Schwäche' /*, 'Energieschaden'*/]));

