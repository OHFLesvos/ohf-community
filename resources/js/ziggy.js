    var Ziggy = {
        namedRoutes: {"api.users.index":{"uri":"api\/users","methods":["GET","HEAD"],"domain":null},"api.users.store":{"uri":"api\/users","methods":["POST"],"domain":null},"api.users.show":{"uri":"api\/users\/{user}","methods":["GET","HEAD"],"domain":null},"api.users.update":{"uri":"api\/users\/{user}","methods":["PUT","PATCH"],"domain":null},"api.users.destroy":{"uri":"api\/users\/{user}","methods":["DELETE"],"domain":null},"api.users.roles.index":{"uri":"api\/users\/{user}\/roles","methods":["GET","HEAD"],"domain":null},"api.users.relationships.roles.index":{"uri":"api\/users\/{user}\/relationships\/roles","methods":["GET","HEAD"],"domain":null},"api.users.relationships.roles.store":{"uri":"api\/users\/{user}\/relationships\/roles","methods":["POST"],"domain":null},"api.users.relationships.roles.update":{"uri":"api\/users\/{user}\/relationships\/roles","methods":["PUT"],"domain":null},"api.users.relationships.roles.destroy":{"uri":"api\/users\/{user}\/relationships\/roles","methods":["DELETE"],"domain":null},"api.roles.index":{"uri":"api\/roles","methods":["GET","HEAD"],"domain":null},"api.roles.store":{"uri":"api\/roles","methods":["POST"],"domain":null},"api.roles.show":{"uri":"api\/roles\/{role}","methods":["GET","HEAD"],"domain":null},"api.roles.update":{"uri":"api\/roles\/{role}","methods":["PUT","PATCH"],"domain":null},"api.roles.destroy":{"uri":"api\/roles\/{role}","methods":["DELETE"],"domain":null},"api.roles.users.index":{"uri":"api\/roles\/{role}\/users","methods":["GET","HEAD"],"domain":null},"api.roles.administrators.index":{"uri":"api\/roles\/{role}\/administrators","methods":["GET","HEAD"],"domain":null},"api.roles.relationships.users.index":{"uri":"api\/roles\/{role}\/relationships\/users","methods":["GET","HEAD"],"domain":null},"api.roles.relationships.users.store":{"uri":"api\/roles\/{role}\/relationships\/users","methods":["POST"],"domain":null},"api.roles.relationships.users.update":{"uri":"api\/roles\/{role}\/relationships\/users","methods":["PUT"],"domain":null},"api.roles.relationships.users.destroy":{"uri":"api\/roles\/{role}\/relationships\/users","methods":["DELETE"],"domain":null},"api.roles.relationships.administrators.index":{"uri":"api\/roles\/{role}\/relationships\/administrators","methods":["GET","HEAD"],"domain":null},"api.roles.relationships.administrators.store":{"uri":"api\/roles\/{role}\/relationships\/administrators","methods":["POST"],"domain":null},"api.roles.relationships.administrators.update":{"uri":"api\/roles\/{role}\/relationships\/administrators","methods":["PUT"],"domain":null},"api.roles.relationships.administrators.destroy":{"uri":"api\/roles\/{role}\/relationships\/administrators","methods":["DELETE"],"domain":null},"api.fundraising.donors.count":{"uri":"api\/fundraising\/donors\/count","methods":["GET","HEAD"],"domain":null},"api.fundraising.donors.emails":{"uri":"api\/fundraising\/donors\/emails","methods":["GET","HEAD"],"domain":null},"api.fundraising.donors.languages":{"uri":"api\/fundraising\/donors\/languages","methods":["GET","HEAD"],"domain":null},"api.fundraising.donors.countries":{"uri":"api\/fundraising\/donors\/countries","methods":["GET","HEAD"],"domain":null},"api.fundraising.donors.registrations":{"uri":"api\/fundraising\/donors\/registrations","methods":["GET","HEAD"],"domain":null},"api.fundraising.donors.index":{"uri":"api\/fundraising\/donors","methods":["GET","HEAD"],"domain":null},"api.fundraising.donations.registrations":{"uri":"api\/fundraising\/donations\/registrations","methods":["GET","HEAD"],"domain":null},"api.fundraising.donations.index":{"uri":"api\/fundraising\/donations","methods":["GET","HEAD"],"domain":null},"api.fundraising.donors.comments.index":{"uri":"api\/fundraising\/donors\/{donor}\/comments","methods":["GET","HEAD"],"domain":null},"api.fundraising.donors.comments.store":{"uri":"api\/fundraising\/donors\/{donor}\/comments","methods":["POST"],"domain":null},"api.fundraising.donors.tags.index":{"uri":"api\/fundraising\/donors\/{donor}\/tags","methods":["GET","HEAD"],"domain":null},"api.fundraising.donors.tags.store":{"uri":"api\/fundraising\/donors\/{donor}\/tags","methods":["POST"],"domain":null},"api.fundraising.tags.index":{"uri":"api\/fundraising\/tags","methods":["GET","HEAD"],"domain":null},"api.comments.show":{"uri":"api\/comments\/{comment}","methods":["GET","HEAD"],"domain":null},"api.comments.update":{"uri":"api\/comments\/{comment}","methods":["PUT","PATCH"],"domain":null},"api.comments.destroy":{"uri":"api\/comments\/{comment}","methods":["DELETE"],"domain":null},"api.people.index":{"uri":"api\/people","methods":["GET","HEAD"],"domain":null},"api.people.filterPersons":{"uri":"api\/people\/filterPersons","methods":["GET","HEAD"],"domain":null},"api.people.store":{"uri":"api\/people","methods":["POST"],"domain":null},"api.people.show":{"uri":"api\/people\/{person}","methods":["GET","HEAD"],"domain":null},"api.people.update":{"uri":"api\/people\/{person}","methods":["PUT"],"domain":null},"api.people.updateGender":{"uri":"api\/people\/{person}\/gender","methods":["PATCH"],"domain":null},"api.people.updateDateOfBirth":{"uri":"api\/people\/{person}\/date_of_birth","methods":["PATCH"],"domain":null},"api.people.updateNationality":{"uri":"api\/people\/{person}\/nationality","methods":["PATCH"],"domain":null},"api.people.updatePoliceNo":{"uri":"api\/people\/{person}\/updatePoliceNo","methods":["PATCH"],"domain":null},"api.people.updateRemarks":{"uri":"api\/people\/{person}\/remarks","methods":["PATCH"],"domain":null},"api.people.registerCard":{"uri":"api\/people\/{person}\/card","methods":["PATCH"],"domain":null},"api.people.reporting.numbers":{"uri":"api\/people\/reporting\/numbers","methods":["GET","HEAD"],"domain":null},"api.people.reporting.nationalities":{"uri":"api\/people\/reporting\/nationalities","methods":["GET","HEAD"],"domain":null},"api.people.reporting.genderDistribution":{"uri":"api\/people\/reporting\/genderDistribution","methods":["GET","HEAD"],"domain":null},"api.people.reporting.ageDistribution":{"uri":"api\/people\/reporting\/ageDistribution","methods":["GET","HEAD"],"domain":null},"api.people.reporting.registrationsPerDay":{"uri":"api\/people\/reporting\/registrationsPerDay","methods":["GET","HEAD"],"domain":null},"api.people.reporting.monthlySummary":{"uri":"api\/people\/reporting\/monthlySummary","methods":["GET","HEAD"],"domain":null},"api.bank.withdrawal.dailyStats":{"uri":"api\/bank\/withdrawal\/dailyStats","methods":["GET","HEAD"],"domain":null},"api.bank.withdrawal.transactions":{"uri":"api\/bank\/withdrawal\/transactions","methods":["GET","HEAD"],"domain":null},"api.bank.withdrawal.search":{"uri":"api\/bank\/withdrawal\/search","methods":["GET","HEAD"],"domain":null},"api.bank.withdrawal.person":{"uri":"api\/bank\/withdrawal\/persons\/{person}","methods":["GET","HEAD"],"domain":null},"api.bank.withdrawal.handoutCoupon":{"uri":"api\/bank\/person\/{person}\/couponType\/{couponType}\/handout","methods":["POST"],"domain":null},"api.bank.withdrawal.undoHandoutCoupon":{"uri":"api\/bank\/person\/{person}\/couponType\/{couponType}\/handout","methods":["DELETE"],"domain":null},"api.bank.reporting.withdrawals":{"uri":"api\/bank\/withdrawals","methods":["GET","HEAD"],"domain":null},"api.bank.reporting.couponsHandedOutPerDay":{"uri":"api\/bank\/withdrawals\/chart\/couponsHandedOutPerDay\/{coupon}","methods":["GET","HEAD"],"domain":null},"api.bank.reporting.visitors":{"uri":"api\/bank\/visitors","methods":["GET","HEAD"],"domain":null},"api.bank.reporting.visitorsPerDay":{"uri":"api\/bank\/visitors\/chart\/visitorsPerDay","methods":["GET","HEAD"],"domain":null},"api.bank.reporting.visitorsPerWeek":{"uri":"api\/bank\/visitors\/chart\/visitorsPerWeek","methods":["GET","HEAD"],"domain":null},"api.bank.reporting.visitorsPerMonth":{"uri":"api\/bank\/visitors\/chart\/visitorsPerMonth","methods":["GET","HEAD"],"domain":null},"api.bank.reporting.visitorsPerYear":{"uri":"api\/bank\/visitors\/chart\/visitorsPerYear","methods":["GET","HEAD"],"domain":null},"api.bank.reporting.avgVisitorsPerDayOfWeek":{"uri":"api\/bank\/visitors\/chart\/avgVisitorsPerDayOfWeek","methods":["GET","HEAD"],"domain":null},"api.cmtyvol.ageDistribution":{"uri":"api\/cmtyvol\/ageDistribution","methods":["GET","HEAD"],"domain":null},"api.cmtyvol.nationalityDistribution":{"uri":"api\/cmtyvol\/nationalityDistribution","methods":["GET","HEAD"],"domain":null},"api.cmtyvol.genderDistribution":{"uri":"api\/cmtyvol\/genderDistribution","methods":["GET","HEAD"],"domain":null},"api.cmtyvol.index":{"uri":"api\/cmtyvol","methods":["GET","HEAD"],"domain":null},"api.cmtyvol.show":{"uri":"api\/cmtyvol\/{cmtyvol}","methods":["GET","HEAD"],"domain":null},"api.library.lending.stats":{"uri":"api\/library\/lending\/stats","methods":["GET","HEAD"],"domain":null},"api.library.lending.persons":{"uri":"api\/library\/lending\/persons","methods":["GET","HEAD"],"domain":null},"api.library.lending.books":{"uri":"api\/library\/lending\/books","methods":["GET","HEAD"],"domain":null},"api.library.lending.person":{"uri":"api\/library\/lending\/person\/{person}","methods":["GET","HEAD"],"domain":null},"api.library.lending.lendBookToPerson":{"uri":"api\/library\/lending\/person\/{person}\/lendBook","methods":["POST"],"domain":null},"api.library.lending.extendBookToPerson":{"uri":"api\/library\/lending\/person\/{person}\/extendBook","methods":["POST"],"domain":null},"api.library.lending.returnBookFromPerson":{"uri":"api\/library\/lending\/person\/{person}\/returnBook","methods":["POST"],"domain":null},"api.library.lending.personLog":{"uri":"api\/library\/lending\/person\/{person}\/log","methods":["GET","HEAD"],"domain":null},"api.library.lending.book":{"uri":"api\/library\/lending\/book\/{book}","methods":["GET","HEAD"],"domain":null},"api.library.lending.lendBook":{"uri":"api\/library\/lending\/book\/{book}\/lend","methods":["POST"],"domain":null},"api.library.lending.extendBook":{"uri":"api\/library\/lending\/book\/{book}\/extend","methods":["POST"],"domain":null},"api.library.lending.returnBook":{"uri":"api\/library\/lending\/book\/{book}\/return","methods":["POST"],"domain":null},"api.library.lending.bookLog":{"uri":"api\/library\/lending\/book\/{book}\/log","methods":["GET","HEAD"],"domain":null},"api.library.books.filter":{"uri":"api\/library\/books\/filter","methods":["GET","HEAD"],"domain":null},"api.library.books.findIsbn":{"uri":"api\/library\/books\/findIsbn","methods":["GET","HEAD"],"domain":null},"api.library.books.index":{"uri":"api\/library\/books","methods":["GET","HEAD"],"domain":null},"api.library.books.store":{"uri":"api\/library\/books","methods":["POST"],"domain":null},"api.library.books.show":{"uri":"api\/library\/books\/{book}","methods":["GET","HEAD"],"domain":null},"api.library.books.update":{"uri":"api\/library\/books\/{book}","methods":["PUT","PATCH"],"domain":null},"api.library.books.destroy":{"uri":"api\/library\/books\/{book}","methods":["DELETE"],"domain":null},"api.countries":{"uri":"api\/countries","methods":["GET","HEAD"],"domain":null},"api.languages":{"uri":"api\/languages","methods":["GET","HEAD"],"domain":null},"library.lending.index":{"uri":"library\/lending","methods":["GET","HEAD"],"domain":null},"library.lending.person":{"uri":"library\/lending\/person\/{person}","methods":["GET","HEAD"],"domain":null},"library.lending.book":{"uri":"library\/lending\/book\/{book}","methods":["GET","HEAD"],"domain":null},"library.export":{"uri":"library\/export","methods":["GET","HEAD"],"domain":null},"library.doExport":{"uri":"library\/doExport","methods":["POST"],"domain":null},"library.report":{"uri":"library\/report","methods":["GET","HEAD"],"domain":null},"library.books.index":{"uri":"library\/books","methods":["GET","HEAD"],"domain":null},"library.books.create":{"uri":"library\/books\/create","methods":["GET","HEAD"],"domain":null},"library.books.edit":{"uri":"library\/books\/{book}\/edit","methods":["GET","HEAD"],"domain":null}},
        baseUrl: 'https://ohf.test/',
        baseProtocol: 'https',
        baseDomain: 'ohf.test',
        basePort: false,
        defaultParameters: []
    };

    if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
        for (var name in window.Ziggy.namedRoutes) {
            Ziggy.namedRoutes[name] = window.Ziggy.namedRoutes[name];
        }
    }

    export {
        Ziggy
    }
