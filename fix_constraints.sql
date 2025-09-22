-- Fix foreign key constraints for AnsweredQuestion
SET FOREIGN_KEY_CHECKS=0;

-- Drop any existing foreign keys
ALTER TABLE answered_question DROP FOREIGN KEY FK_E0F04F5A4FA90DB2;
ALTER TABLE answered_question DROP FOREIGN KEY FK_E0F04F5A1E27F6BF;

-- Change column types to allow NULL
ALTER TABLE answered_question CHANGE optione_id optione_id INT DEFAULT NULL;
ALTER TABLE answered_question CHANGE question_id question_id INT DEFAULT NULL;

-- Add proper foreign key constraints
ALTER TABLE answered_question ADD CONSTRAINT FK_E0F04F5A4FA90DB2 FOREIGN KEY (optione_id) REFERENCES `option` (id) ON DELETE SET NULL;
ALTER TABLE answered_question ADD CONSTRAINT FK_E0F04F5A1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE SET NULL;

-- Update user_mission to cascade on delete
ALTER TABLE user_mission DROP FOREIGN KEY FK_C86AEC7E59949888;
ALTER TABLE user_mission ADD CONSTRAINT FK_C86AEC7E59949888 FOREIGN KEY (user_language_course_id) REFERENCES user_language_course (id) ON DELETE CASCADE;

SET FOREIGN_KEY_CHECKS=1;