# Wwwision.AssetConstraints

Simple package to constraint access to Neos.Media assets based on tags, content type or asset collection

**NOTE:** The functionality of this package has been [ported](https://github.com/neos/neos-development-collection/pull/1723) to the Neos Core with version 3.3 in the meantime

## Usage

1. Drop package into your (Neos) installation
2. Add policies to your main package `Policy.yaml`
3. Adjust `Settings` and `NodeTypes` configuration to your needs

## Features

### New Asset privileges:

This package comes with Entity Privileges allowing to restrict reading of `Assets` based on several attributes:

#### Restrict read access to `Assets` based on their *media type*

*Policy.yaml:*
```yaml
privilegeTargets:
  'Wwwision\AssetConstraints\Security\Authorization\Privilege\ReadAssetPrivilege':
    'Some.Package:ReadAllPDFs':
      matcher: 'hasMediaType("application/pdf")'
```

#### Restrict read access to `Assets` based on *Tag*

*Policy.yaml:*
```yaml
privilegeTargets:
  'Wwwision\AssetConstraints\Security\Authorization\Privilege\ReadAssetPrivilege':
    'Some.Package:ReadConfidentialAssets':
      matcher: 'isTagged("confidential")'
```

#### Restrict read access to `Assets` based on *Asset Collection*

*Policy.yaml:*
```yaml
privilegeTargets:
  'Wwwision\AssetConstraints\Security\Authorization\Privilege\ReadAssetPrivilege':
    'Some.Package:ReadSpecialAssets':
      matcher: 'isInCollection("some-collection")'
```

Of course you can combine the three matchers like:

```yaml
privilegeTargets:
  'Wwwision\AssetConstraints\Security\Authorization\Privilege\ReadAssetPrivilege':
    'Some.Package:ReadConfidentialPdfs':
      matcher: 'hasMediaType("application/pdf") && isTagged("confidential")'
```

#### Restrict read access to `Tags` based on *Tag label*

*Policy.yaml:*
```yaml
privilegeTargets:
  'Wwwision\AssetConstraints\Security\Authorization\Privilege\ReadTagPrivilege':
    'Some.Package:ReadConfidentialTags':
      matcher: 'isLabeled("confidential")'
```

#### Restrict read access to `Asset Collections` based on *Collection title*

*Policy.yaml:*
```yaml
privilegeTargets:
  'Wwwision\AssetConstraints\Security\Authorization\Privilege\ReadAssetCollectionPrivilege':
    'Some.Package:ReadSpecialAssetCollection':
      matcher: 'isTitled("some-collection")'
```

### Custom Editors to set Asset Collection based on node properties:

When uploading new `Assets` using the Neos inspector, they will be added to the current site's default `Asset Collection`
if one is configured in the *Sites Management module*.

Unfortunately this mechanism is not (yet) flexible enough to set the collection based on other characteristics (the
currently selected node for example).

This package therefore adds two specialized Inspector editors for Asset/Image uploads that send the current node along
with the upload-data to the server. Besides it hooks into the asset creation process (via AOP) to add the uploaded
`Asset` to an `Asset Collection` based on the current node.

The default behavior is to grab the closest document node, evaluate it's "assetCollection" and adds the Asset to that
collection if it succeeded.

This package also comes with a `DataSource` to allow for selecting the `AssetCollection`.

#### Adding "assetCollection" property to all Document nodes:

*NodeTypes.yaml:*
```yaml
'Neos.Neos:Document':
  ui:
    inspector:
      groups:
        'assets':
          label: 'Assets'
  properties:
    'assetCollection':
      ui:
        label: 'Asset Collection'
        inspector:
          group: 'assets'
          editor: 'Content/Inspector/Editors/SelectBoxEditor'
          editorOptions:
            dataSourceIdentifier: 'wwwision-assetconstraints-assetcollections'
            allowEmpty: true
            placeholder: 'Asset Collection for uploads'
```

**NOTE:** Usually you *don't* want to add a property to *all* Document nodes (including shortcuts, ...) but to a more
specific node type such as `Your.Package:Page`.

#### Adjusting the behavior of the AOP aspect:

As mentioned above, the default behavior of the AOP aspect is to check for a property called "assetCollection" in the
closest `Neos.Neos:Document` node of the node the asset was uploaded to.

This can be adjusted via Settings. Imagine you have a custom node type `Your.Package:MainPage` that contains the
target assetCollection in a property "collection":

*Settings.yaml:*
```yaml
Wwwision:
  AssetConstraints:
    nodeLookup:
      nodeFilter: '[instanceof Your.Package:MainPage]'
      propertyName: 'collection'
```

## Example Policy

Given you have three "groups" and corresponding roles `Some.Package:Group1Editor`, `Some.Package:Group2Editor` and
`Some.Package:Group3Editor` as well as an administrative role ``Some.Package:Administrator`.

Now, if you have three "Asset Collections" named `group1`, `group2` and `group3` the following `Policy.yaml` would
restrict editors to only see collections and assets corresponding to their role:

```yaml
privilegeTargets:

  'Wwwision\AssetConstraints\Security\Authorization\Privilege\ReadAssetPrivilege':

    'Some.Package:Group1.ReadAssets':
      matcher: 'isInCollection("group1")'
    'Some.Package:Group2.ReadAssets':
      matcher: 'isInCollection("group2")'
    'Some.Package:Group3.ReadAssets':
      matcher: 'isInCollection("group3")'

  'Wwwision\AssetConstraints\Security\Authorization\Privilege\ReadAssetCollectionPrivilege':

    'Some.Package:Group1.ReadCollections':
      matcher: 'isTitled("group1")'
    'Some.Package:Group2.ReadCollections':
      matcher: 'isTitled("group2")'
    'Some.Package:Group3.ReadCollections':
      matcher: 'isTitled("group3")'

roles:

  'Your.Package:Administrator':
    privileges:
      -
        privilegeTarget: 'Some.Package:Group1.ReadAssets'
        permission: GRANT
      -
        privilegeTarget: 'Some.Package:Group1.ReadCollections'
        permission: GRANT
      -
        privilegeTarget: 'Some.Package:Group2.ReadAssets'
        permission: GRANT
      -
        privilegeTarget: 'Some.Package:Group2.ReadCollections'
        permission: GRANT
      -
        privilegeTarget: 'Some.Package:Group3.ReadAssets'
        permission: GRANT
      -
        privilegeTarget: 'Some.Package:Group3.ReadCollections'
        permission: GRANT

  'Your.Package:Group1Editor':
    privileges:
      -
        privilegeTarget: 'Some.Package:Group1.ReadAssets'
        permission: GRANT
      -
        privilegeTarget: 'Some.Package:Group1.ReadCollections'
        permission: GRANT

  'Your.Package:Group2Editor':
    privileges:
      -
        privilegeTarget: 'Some.Package:Group2.ReadAssets'
        permission: GRANT
      -
        privilegeTarget: 'Some.Package:Group2.ReadCollections'
        permission: GRANT

  'Your.Package:Group3Editor':
    privileges:
      -
        privilegeTarget: 'Some.Package:Group3.ReadAssets'
        permission: GRANT
      -
        privilegeTarget: 'Some.Package:Group3.ReadCollections'
        permission: GRANT
```

## Credits

The development of this package was kindly sponsored by Web Essentials!
