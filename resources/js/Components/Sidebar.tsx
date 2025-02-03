import { useState } from "react";
import { Link } from "@inertiajs/react";
import { FaAngleDown, FaAngleUp } from "react-icons/fa6";

export default function Sidebar(){
    const [current, setCurrent] = useState(0);
    return (
        <div className="md:w-1/4 lg:w-1/5 h-screen bg-white p-4 min-w-[240px]">
            <div className="p-2 space-y-2">
                <h3 className={`flex justify-between mouse-pointer ${current === 1 ? 'text-black' : 'text-gray-600'}`} onClick={() => setCurrent(1)} >
                    Students
                    {current === 1 ? (<FaAngleUp />) : (<FaAngleDown />)}
                </h3>
                <nav className={`${current === 1 ? 'block' : 'hidden'} space-y-2`}>
                    <ul className="space-y-2">
                        <li>
                            <Link href={route('students.index')}>All Students</Link>
                        </li>
                        <li>
                            <Link href={route('students.create')}>Add a New Student</Link>
                        </li>
                     </ul>
                </nav>
            </div>
            <div className="p-2 space-y-2">
                <h3 className={`flex justify-between ${current === 2 ? 'text-black' : 'text-gray-600'}`} onClick={() => setCurrent(2)}>
                Semesters
                {current === 2 ? (<FaAngleUp />) : (<FaAngleDown />)}
                </h3>
                <nav className={`${current === 2 ? 'block' : 'hidden'}`}>
                    <ul className="space-y-2">
                       <li>
                            <Link href={route('semesters.index')}>All semesters</Link>
                        </li>
                       <li>
                            <Link href={route('years.index')}>All Academic Years</Link>
                        </li>
                       <li>
                            <Link href={route('years.create')}>Start An Academic Year</Link>
                        </li>
                       <li>
                            <Link href={route('semesters.create')}>Create a New Semester</Link>
                        </li>

                    </ul>
                </nav>
            </div>
            <div className="space-y-2 p-2">
                <h3 className={`flex justify-between ${current === 3 ? 'text-black' : 'text-gray-600'}`} onClick={() => setCurrent(3)}>
                Subjects
                {current === 3 ? (<FaAngleUp />) : (<FaAngleDown />)}
                </h3>
                <nav className={`${current === 3 ? 'block' : 'hidden'}`}>
                    <ul className="space-y-2">
                       <li>
                            <Link href={route('subjects.index')}>All Subjects</Link>
                        </li>
                       <li>
                            <Link href={route('grades.index')}>All Grades</Link>
                        </li>
                       <li>
                            <Link href={route('semesters.create')}>Create a New Subject</Link>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    );
}
