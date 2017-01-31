# Opinion
Plugin Opinion for Magix CMS

###version 

[![release](https://img.shields.io/github/release/magix-cms/opinion.svg)](https://github.com/magix-cms/opinion/releases/latest)

## Description

Mise en place d'un système de témoignages liés aux produit.

## Installation
 * Décompresser l'archive dans le dossier "plugins" de magix cms
 * Connectez-vous dans l'administration de votre site internet
 * Cliquer sur l'onglet plugins du menu déroulant pour sélectionner opinion (Témoignages).
 * Une fois dans le plugin, laisser faire l'auto installation
 * Il ne reste que la configuration du plugin pour correspondre avec vos données.

## Ajout du plugin au thème
 * Copier le contenu du dossier "public" situé dans le dossier skin du plugin et le coller dans votre thème.
 * Si elle n'existe pas déjà, ajouter la ligne suivante à la fin du fichier bootstrap.less ("css/bootstrap/less")

```less
@import (optional) "custom/theme/opinion";
```

 * Pour ajouter le bloc des derniers témoignages sur la page d'accueil, ajouter cette ligne où vous le désirer dans le block main du fichier home.tpl

```smarty
{include file="opinion/brick/last-opinion.tpl"}
```

 * Pour ajouter le bloc des derniers témoignages lié à un produit, ainsi que le formulaire pour poster un témoignége sur une fiche produit,
 ajouter cette ligne dans le block main du fichier catalog/product.tpl
 
```smarty
{include file="opinion/catalog/product-opinion.tpl"}
```
 
 * et la ligne suivante dans le block foot du fichier catalog/product.tpl (ne pas oublier d'indiquer append au block foot pour que la ligne s'ajoute et non pas remplace le contenu du block foot)
 
```smarty
{block name="foot" append}
    {include file="opinion/catalog/product-footer.tpl"}
{/block}
```