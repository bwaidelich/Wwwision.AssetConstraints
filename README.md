# Wwwision.AssetConstraints
Simple package to constraint access to TYPO3.Media assets based on tags, content type or asset collection

**NOTE:** This package is in an **experimental** state at the moment

## Usage

1. Drop package into your (TYPO3 Neos) installation
2. Add policies to your main package `Policy.yaml`

## Example Policy

Given you have three "groups" and corresponding roles `Some.Package:Group1Editor`, `Some.Package:Group2Editor` and `Some.Package:Group3Editor` as well as an administrative role ``Some.Package:Administrator`.
Now, if you have three "Asset Collections" named `group1`, `group2` and `group3` the following `Policy.yaml` would restrict editors to only see collections and assets corresponding to their role:

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
