<?php
/*
 * Copyright (c) 2016 Oliver Schramm
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

use Alchemy\Effect;
use Alchemy\EffectCollection;
use Alchemy\Regency;
use Alchemy\RegencyCollection;

$effectCollection = new EffectCollection();
$regencyCollection = new RegencyCollection();
Regency::setEffectCollection($effectCollection);

// Effects are sorted alphabetically; true/false if positive/negative

$effectCollection->addEffect(new Effect('Blindheit', false));
$effectCollection->addEffect(new Effect('Energieresistenz erhöhen', true));
$effectCollection->addEffect(new Effect('Energieresistenz senken', false));
//$effectCollection->addEffect(new Effect('Energieschaden', false)); // probably non-existent
$effectCollection->addEffect(new Effect('Enthüllung', false));
$effectCollection->addEffect(new Effect('Erfrischung', true));
$effectCollection->addEffect(new Effect('Feder', true));
$effectCollection->addEffect(new Effect('Feuerresistenz erhöhen', true));
$effectCollection->addEffect(new Effect('Feuerresistenz senken', false));
$effectCollection->addEffect(new Effect('Feuerschaden', false));
$effectCollection->addEffect(new Effect('Gegengift', true));
$effectCollection->addEffect(new Effect('Geschick', true));
$effectCollection->addEffect(new Effect('Giftresistenz erhöhen', true));
$effectCollection->addEffect(new Effect('Giftresistenz senken', false));
$effectCollection->addEffect(new Effect('Giftschaden', false));
$effectCollection->addEffect(new Effect('Haarausfall', false));
$effectCollection->addEffect(new Effect('Haarwuchs', false));
$effectCollection->addEffect(new Effect('Halluzinationen', false)); //
$effectCollection->addEffect(new Effect('Heilung', true));
$effectCollection->addEffect(new Effect('Hunger', false));
$effectCollection->addEffect(new Effect('Infizieren', false));
$effectCollection->addEffect(new Effect('Intelligenz', true));
$effectCollection->addEffect(new Effect('Kälteschaden', false)); //
$effectCollection->addEffect(new Effect('Krankheitsresistenz erhöhen', true));
$effectCollection->addEffect(new Effect('Last', false));
$effectCollection->addEffect(new Effect('Lebensenergieentzug', false));
$effectCollection->addEffect(new Effect('leichte Krankheit heilen', true));
$effectCollection->addEffect(new Effect('Magieresistenz erhöhen', true));
$effectCollection->addEffect(new Effect('Magieresistenz senken', false));
$effectCollection->addEffect(new Effect('Manawiederherstellung', true));
$effectCollection->addEffect(new Effect('Manaentzug', false));
$effectCollection->addEffect(new Effect('Nachtsicht', true));
$effectCollection->addEffect(new Effect('Paralyse', false));
$effectCollection->addEffect(new Effect('Paralyse aufheben', true));
$effectCollection->addEffect(new Effect('Physische Resistenz erhöhen', true));
$effectCollection->addEffect(new Effect('Physische Resistenz senken', false));
$effectCollection->addEffect(new Effect('Sättigung', true));
$effectCollection->addEffect(new Effect('Säureschaden', false)); //
$effectCollection->addEffect(new Effect('Schlaf', false));
$effectCollection->addEffect(new Effect('Schminke', true));
$effectCollection->addEffect(new Effect('Schutz', true));
$effectCollection->addEffect(new Effect('Schwäche', false));
$effectCollection->addEffect(new Effect('Schwachsinn', false));
$effectCollection->addEffect(new Effect('schwere Krankheit heilen', true));
$effectCollection->addEffect(new Effect('Stärke', true));
$effectCollection->addEffect(new Effect('Tollpatschigkeit', false));
$effectCollection->addEffect(new Effect('Unsichtbarkeit', true));
$effectCollection->addEffect(new Effect('Zauber auflösen', true));

// Regencies are alphabetically sorted; effects have to be written the same way as above - order is unimportant
// third parameter of constructor can be a default price

$regencyCollection->addRegency(new Regency('Alraune', ['Stärke', 'Intelligenz', 'Giftresistenz erhöhen', 'Blindheit']));
$regencyCollection->addRegency(new Regency('Apfel', ['Stärke', 'Heilung', 'Haarwuchs', 'Last']));
$regencyCollection->addRegency(new Regency('Bims', ['Feder', 'Nachtsicht', 'Feuerresistenz erhöhen', 'Energieresistenz erhöhen']));
$regencyCollection->addRegency(new Regency('Blut', ['Stärke', 'Intelligenz', 'Tollpatschigkeit']));
$regencyCollection->addRegency(new Regency('Blutmoos', ['Geschick', 'Krankheitsresistenz erhöhen', 'Schminke', 'Schwachsinn']));
$regencyCollection->addRegency(new Regency('Champignon', ['Intelligenz', 'Sättigung', 'Haarwuchs', 'Hunger']));
$regencyCollection->addRegency(new Regency('Coeliumerz', ['Nachtsicht', 'Energieresistenz erhöhen', 'Enthüllung' /*, 'Energieschaden'*/]));
$regencyCollection->addRegency(new Regency('Dämonenknochen', ['Physische Resistenz erhöhen', 'Manawiederherstellung', 'Magieresistenz senken', 'Physische Resistenz senken']));
$regencyCollection->addRegency(new Regency('Diamanterz', ['Physische Resistenz erhöhen', 'Unsichtbarkeit', 'Schutz', 'Lebensenergieentzug']));
$regencyCollection->addRegency(new Regency('Drachenblut', ['Magieresistenz erhöhen', 'Zauber auflösen', 'Manawiederherstellung', 'Schlaf']));
$regencyCollection->addRegency(new Regency('Efeu', ['Heilung', 'Unsichtbarkeit', 'Schlaf', 'Giftschaden']));
$regencyCollection->addRegency(new Regency('Eisenerz', ['Stärke', 'Schutz', 'Tollpatschigkeit', 'Schwachsinn']));
$regencyCollection->addRegency(new Regency('Fingerhut', ['leichte Krankheit heilen', 'Paralyse aufheben', 'Energieresistenz senken']));
$regencyCollection->addRegency(new Regency('Fledermausflügel', ['Feder', 'Krankheitsresistenz erhöhen', 'Manaentzug', 'Blindheit']));
$regencyCollection->addRegency(new Regency('Fliegenpilz', ['Manawiederherstellung', 'Manaentzug', 'Giftschaden', 'Lebensenergieentzug']));
$regencyCollection->addRegency(new Regency('Ginsengwurzel', ['Heilung', 'leichte Krankheit heilen', 'Erfrischung', 'Gegengift']));
$regencyCollection->addRegency(new Regency('Grabmoos', ['Heilung', 'Schutz', 'Halluzinationen', 'Schminke']));
$regencyCollection->addRegency(new Regency('grüne Traube', ['Stärke', 'Heilung', 'Haarwuchs', 'Paralyse']));
$regencyCollection->addRegency(new Regency('Henkerskappe', ['Zauber auflösen', 'Manawiederherstellung', 'Paralyse', 'Blindheit']));
$regencyCollection->addRegency(new Regency('Kaktus', ['Feder', 'Krankheitsresistenz erhöhen', 'Sättigung', 'Haarausfall']));
$regencyCollection->addRegency(new Regency('Knoblauch', ['Giftresistenz erhöhen', 'Krankheitsresistenz erhöhen', 'Gegengift', 'schwere Krankheit heilen']));
$regencyCollection->addRegency(new Regency('Knochen', ['Magieresistenz erhöhen', 'Schwäche', 'Kälteschaden']));
$regencyCollection->addRegency(new Regency('Krötenlaich', ['Paralyse aufheben', 'Tollpatschigkeit', 'Infizieren', 'Säureschaden']));
$regencyCollection->addRegency(new Regency('Kupfererz', ['Geschick', 'Schutz', 'Schwäche', 'Schwachsinn']));
$regencyCollection->addRegency(new Regency('Lehm', ['Physische Resistenz erhöhen', 'Sättigung', 'Blindheit', 'Säureschaden']));
$regencyCollection->addRegency(new Regency('Limone', ['leichte Krankheit heilen', 'Gegengift', 'Säureschaden', 'Feuerresistenz senken']));
$regencyCollection->addRegency(new Regency('Molchauge', ['Schwäche', 'Haarausfall', 'Halluzinationen', 'Physische Resistenz senken']));
$regencyCollection->addRegency(new Regency('Nachtschatten', ['Nachtsicht', 'schwere Krankheit heilen', 'Giftschaden', 'Lebensenergieentzug']));
$regencyCollection->addRegency(new Regency('Obsidian', ['Unsichtbarkeit', 'leichte Krankheit heilen', 'Giftschaden']));
$regencyCollection->addRegency(new Regency('Pfirsich', ['Geschick', 'leichte Krankheit heilen', 'Paralyse aufheben', 'Erfrischung']));
$regencyCollection->addRegency(new Regency('Pyrianerz', ['Feuerresistenz erhöhen', 'Last', 'Enthüllung', 'Feuerschaden']));
$regencyCollection->addRegency(new Regency('Rattenfleisch', ['Schwachsinn', 'Schlaf', 'Infizieren', 'Giftresistenz senken']));
$regencyCollection->addRegency(new Regency('roher Vogel', ['Energieresistenz erhöhen', 'Sättigung', 'Haarwuchs', 'Infizieren']));
$regencyCollection->addRegency(new Regency('Schlangenschuppe', ['Nachtsicht', 'Hunger', 'Haarausfall', 'Infizieren']));
$regencyCollection->addRegency(new Regency('schwarze Perle', ['Stärke', 'Zauber auflösen', 'Erfrischung', 'Blindheit']));
$regencyCollection->addRegency(new Regency('Schwefel', ['Intelligenz', 'Schlaf', 'Manaentzug', 'Lebensenergieentzug']));
$regencyCollection->addRegency(new Regency('Schwefelasche', ['Geschick', 'Giftschaden', 'Feuerschaden', 'Feuerresistenz senken']));
$regencyCollection->addRegency(new Regency('Seerose', ['schwere Krankheit heilen', 'Schwachsinn', 'Schlaf', 'Haarausfall']));
$regencyCollection->addRegency(new Regency('Spinnenseide', ['Feder', 'Nachtsicht', 'Giftresistenz erhöhen', 'Paralyse']));
$regencyCollection->addRegency(new Regency('Torf', ['Zauber auflösen', 'Last', 'Giftresistenz senken']));
$regencyCollection->addRegency(new Regency('totes Holz', ['Stärke', 'leichte Krankheit heilen', 'Kälteschaden', 'Halluzinationen']));
$regencyCollection->addRegency(new Regency('Traube', ['Stärke', 'Heilung', 'Feder', 'Hunger']));
$regencyCollection->addRegency(new Regency('Vulkanasche', ['Feuerresistenz erhöhen', 'Schwäche', 'Magieresistenz senken', 'Säureschaden']));
$regencyCollection->addRegency(new Regency('Weicheisen', ['Feuerresistenz erhöhen', 'Sättigung', 'Last', 'Energieresistenz senken']));
$regencyCollection->addRegency(new Regency('Wyrmherz', ['Heilung', 'Magieresistenz erhöhen', 'Unsichtbarkeit', 'Schutz']));
$regencyCollection->addRegency(new Regency('Zitrone', ['leichte Krankheit heilen', 'Gegengift', 'Feuerresistenz senken']));
$regencyCollection->addRegency(new Regency('Zwiebel', ['Krankheitsresistenz erhöhen', 'Paralyse aufheben', 'Schwäche' /*, 'Energieschaden'*/]));

// Restore prices
$prices = @file_get_contents('price_data.txt', FILE_TEXT);
$regencyCollection->setPrices($prices);
