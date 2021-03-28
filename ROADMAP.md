# Pheature Flags Development Roadmap

**Get full control over your application releases**

The Pheature flags release management system that allows to have full control over different features running
in a web application, or other software system. It separates release process from deployment, 
applies custom rollout strategies based in segments, allows applying instant rollbacks to failing features, 
reduces the technical debt on complex business experiments, and helps both developers and managers building trust 
between them.

## What do we need?

This is our open roadmap, here are listed our current work in progress, todos, and general project development status.
In the next [milestones](), you can find the more important things where you can contribute, also we have created 
an [open canvas board]() where are every [issue]() and each status.
Please check out our [contribution guidelines]() to help us in any way, and don't hesitate to ask everything, 
we are wiling to hear you.

## Milestones

**Milestone:** [Toggle management system]()

> The toggle system allows both developers and managers setting up features enabled or disabled, and applying 
> different rollout strategies based in segmentation and environments.

* [](): Toggle core package
* [](): Toggle model package
* [](): In Memory toggle package
* [](): Toggle CRUD package
* [](): Toggle API package
* [](): Toggle UI
* [](): Toggle Event-Sourced package
* [](): DBal toggle package
* [](): DBal toggle infra templates
* [](): Toggle Cache package

**Milestone:** [Client API]()

> Crazy fast Client API, gets enabled and disabled features for each user in a system. 

* [](): REST API
* [](): Async server
* [](): Client traffic Event-store

**Milestone:** [SDKs]()

> Integrate Pheature flags in any distributed software system.

* [](): PHP SKD
* [](): Node SDK
* [](): Vanilla JS SDK
* [](): Android SDK
* [](): IOS SDK
* [](): Terraform SDK?

**Milestone:** [Community packages]()

> Use Pheature flags out of the box in you favourite PHP framework.

* [](): Standalone Prototype
* [](): Laravel Package
* [](): Laravel Prototype
* [](): Symfony Bundle
* [](): Symfony Prototype
* [](): Laminas and Mezzio package
* [](): Laminas and Mezzio Prototypes
* [](): Wordpress plugin
* [](): Wordpress prototype
* [](): Drupal module
* [](): Drupal prototype

**Milestone:** [Team management system]()

> The team management system allows managing Pheature toggles in a more structured way, by adding members and 
> permission based roles

* [](): Team core package
* [](): Team model package
* [](): In Memory team package
* [](): Team CRUD package
* [](): Team API package
* [](): Team UI
* [](): Team Event-Sourced package
* [](): DBal team package
* [](): DBal team infra templates

**Milestone:** [Security implementations]()

> Authentication strategies to consume client API from untrusted networks.

* [](): Security core package
* [](): Security model package
* [](): Security Basic auth package
* [](): Security JWT package
* [](): Security OAuth package
