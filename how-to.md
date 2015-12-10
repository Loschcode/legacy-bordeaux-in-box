HOW TO
====

The project Bordeaux in Box is getting big. I had to write a guideline in case any developer join the project. This file is all about sensitive changes you could make within the already working code.


HOW TO : ADD A SPECIAL QUESTION / ANSWER TYPE
====

You can easily add question types by modifying the `bdxnbx.php` config file. Then you have to enter the `OrderController` matching view where the form takes place and add whatever you want in it. You also have to change the validation rules within the `postOrder` method in this class.

Sadly, I had to make a special question once ; the `children_details` which contains many dropdowns to fit the needs of the project.

It's a complex question that involve `user_answers`.`referent_id` field as the multiple-answers are all linked between each others.

To make a special field and make it work with the whole system you must be careful about :

  - The view itself where you put all the fields you want and set it as array if there are many.
  - The rules linked to the post method that appears when the user validate what he wrote.
  - The admin section where there are similar validations (but not all, the admin is more free of his moves) and the exact same fields written.
  - The filters that could contain special `answer` that has to be converted and understood through the system.
  - The statistics within the section use it too

Those reminders should be enough for now.